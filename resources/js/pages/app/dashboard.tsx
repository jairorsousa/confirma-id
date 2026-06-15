import InputError from '@/components/input-error';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/react';
import { Check, Clipboard, FileImage, IdCard, LoaderCircle, Send, ShieldCheck, Upload } from 'lucide-react';
import { FormEventHandler, useMemo, useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Area do usuario',
        href: '/app',
    },
];

type Profile = {
    full_name: string;
    cpf: string;
    birth_date: string | null;
    phone: string | null;
} | null;

type Verification = {
    id: number;
    attempt_number: number;
    document_type: string;
    status: VerificationStatus;
    verification_code: string | null;
    submitted_at: string | null;
    approved_at: string | null;
    expires_at: string | null;
    latest_review: {
        decision: string;
        reason: string | null;
        notes: string | null;
    } | null;
} | null;

type VerificationStatus = 'pending' | 'under_review' | 'approved' | 'rejected' | 'correction_requested' | 'blocked';

type VerificationForm = {
    full_name: string;
    cpf: string;
    birth_date: string;
    phone: string;
    document_type: string;
    document_front: File | null;
    document_back: File | null;
    selfie: File | null;
    accept_terms: boolean;
    accept_privacy: boolean;
};

const steps = ['Dados pessoais', 'Documento', 'Frente', 'Verso', 'Selfie', 'Confirmacao'];

const statusCopy: Record<VerificationStatus, { label: string; tone: 'default' | 'secondary' | 'destructive' | 'outline'; message: string }> = {
    pending: {
        label: 'Pendente',
        tone: 'outline',
        message: 'Complete o envio para iniciar a analise.',
    },
    under_review: {
        label: 'Em analise',
        tone: 'secondary',
        message: 'Recebemos seus documentos. Nossa equipe esta analisando sua identidade.',
    },
    approved: {
        label: 'Aprovado',
        tone: 'default',
        message: 'Identidade validada. Seu codigo ConfirmaID esta disponivel.',
    },
    rejected: {
        label: 'Reprovado',
        tone: 'destructive',
        message: 'A verificacao foi negada. Revise o motivo e envie uma nova tentativa.',
    },
    correction_requested: {
        label: 'Correcao solicitada',
        tone: 'outline',
        message: 'Nossa equipe solicitou ajustes nas informacoes enviadas.',
    },
    blocked: {
        label: 'Bloqueado',
        tone: 'destructive',
        message: 'Esta verificacao esta bloqueada e nao pode ser utilizada.',
    },
};

function digitsOnly(value: string) {
    return value.replace(/\D/g, '');
}

function previewFor(file: File | null) {
    return file ? URL.createObjectURL(file) : null;
}

function FileUploadField({
    id,
    label,
    file,
    error,
    onChange,
}: {
    id: keyof Pick<VerificationForm, 'document_front' | 'document_back' | 'selfie'>;
    label: string;
    file: File | null;
    error?: string;
    onChange: (file: File | null) => void;
}) {
    const preview = useMemo(() => previewFor(file), [file]);

    return (
        <div className="grid gap-3">
            <Label htmlFor={id}>{label}</Label>
            <label className="border-sidebar-border/70 hover:bg-muted/40 flex min-h-44 cursor-pointer flex-col items-center justify-center gap-3 rounded-md border border-dashed px-4 py-5 text-center transition">
                {preview ? (
                    <img src={preview} alt="" className="h-36 w-full max-w-sm rounded-md object-cover" />
                ) : (
                    <>
                        <Upload className="text-muted-foreground size-8" />
                        <span className="text-muted-foreground text-sm">JPG, PNG ou WebP ate 5 MB</span>
                    </>
                )}
                <Input
                    id={id}
                    type="file"
                    accept="image/jpeg,image/png,image/webp"
                    className="sr-only"
                    onChange={(event) => onChange(event.target.files?.[0] ?? null)}
                />
            </label>
            {file && <p className="text-muted-foreground text-xs">{file.name}</p>}
            <InputError message={error} />
        </div>
    );
}

export default function UserDashboard({ profile, verification, can_submit }: { profile: Profile; verification: Verification; can_submit: boolean }) {
    const [step, setStep] = useState(0);
    const [copied, setCopied] = useState(false);
    const currentStatus = verification ? statusCopy[verification.status] : statusCopy.pending;

    const { data, setData, post, processing, errors, clearErrors } = useForm<VerificationForm>({
        full_name: profile?.full_name ?? '',
        cpf: profile?.cpf ?? '',
        birth_date: profile?.birth_date?.slice(0, 10) ?? '',
        phone: profile?.phone ?? '',
        document_type: verification?.document_type ?? '',
        document_front: null,
        document_back: null,
        selfie: null,
        accept_terms: false,
        accept_privacy: false,
    });

    const canAdvance =
        step === 0
            ? data.full_name.length > 2 && digitsOnly(data.cpf).length === 11 && data.birth_date !== '' && data.phone.length >= 8
            : step === 1
              ? data.document_type !== ''
              : step === 2
                ? data.document_front !== null
                : step === 3
                  ? data.document_back !== null
                  : step === 4
                    ? data.selfie !== null
                    : data.accept_terms && data.accept_privacy;

    const submit: FormEventHandler = (event) => {
        event.preventDefault();

        post(route('app.verification.store'), {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => setStep(5),
        });
    };

    const copyCode = async () => {
        if (!verification?.verification_code) {
            return;
        }

        await navigator.clipboard.writeText(verification.verification_code);
        setCopied(true);
        window.setTimeout(() => setCopied(false), 2000);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Area do usuario" />
            <div className="flex h-full flex-1 flex-col gap-6 p-4">
                <section className="border-sidebar-border/70 rounded-md border p-5">
                    <div className="flex flex-col gap-5 md:flex-row md:items-start md:justify-between">
                        <div className="flex max-w-3xl gap-4">
                            <div className="bg-primary text-primary-foreground flex size-11 shrink-0 items-center justify-center rounded-md">
                                <ShieldCheck className="size-5" />
                            </div>
                            <div>
                                <div className="flex flex-wrap items-center gap-3">
                                    <h1 className="text-foreground text-2xl font-semibold tracking-normal">Verificacao de identidade</h1>
                                    <Badge variant={currentStatus.tone}>{currentStatus.label}</Badge>
                                </div>
                                <p className="text-muted-foreground mt-2 text-sm leading-6">{currentStatus.message}</p>
                                {verification?.latest_review?.reason && (
                                    <p className="bg-muted text-muted-foreground mt-3 rounded-md px-3 py-2 text-sm">
                                        {verification.latest_review.reason}
                                    </p>
                                )}
                            </div>
                        </div>

                        {verification?.verification_code && (
                            <div className="bg-muted/30 w-full rounded-md border p-4 md:w-72">
                                <p className="text-muted-foreground text-xs font-medium uppercase">Codigo ConfirmaID</p>
                                <div className="mt-2 flex items-center justify-between gap-3">
                                    <strong className="text-xl font-semibold tracking-normal">{verification.verification_code}</strong>
                                    <Button type="button" size="icon" variant="outline" onClick={copyCode}>
                                        {copied ? <Check className="size-4" /> : <Clipboard className="size-4" />}
                                    </Button>
                                </div>
                            </div>
                        )}
                    </div>
                </section>

                {can_submit ? (
                    <form onSubmit={submit} className="grid gap-6 lg:grid-cols-[260px_1fr]">
                        <nav className="border-sidebar-border/70 rounded-md border p-3">
                            <ol className="grid gap-1">
                                {steps.map((item, index) => (
                                    <li key={item}>
                                        <button
                                            type="button"
                                            className={`flex w-full items-center gap-3 rounded-md px-3 py-2 text-left text-sm transition ${
                                                step === index ? 'bg-primary text-primary-foreground' : 'hover:bg-muted'
                                            }`}
                                            onClick={() => setStep(index)}
                                        >
                                            <span className="flex size-7 shrink-0 items-center justify-center rounded-full border text-xs">
                                                {index + 1}
                                            </span>
                                            <span>{item}</span>
                                        </button>
                                    </li>
                                ))}
                            </ol>
                        </nav>

                        <section className="border-sidebar-border/70 rounded-md border p-5">
                            <InputError message={errors.verification} className="mb-4" />

                            {step === 0 && (
                                <div className="grid gap-5 md:grid-cols-2">
                                    <div className="grid gap-2 md:col-span-2">
                                        <Label htmlFor="full_name">Nome completo</Label>
                                        <Input id="full_name" value={data.full_name} onChange={(e) => setData('full_name', e.target.value)} />
                                        <InputError message={errors.full_name} />
                                    </div>
                                    <div className="grid gap-2">
                                        <Label htmlFor="cpf">CPF</Label>
                                        <Input
                                            id="cpf"
                                            inputMode="numeric"
                                            maxLength={11}
                                            value={data.cpf}
                                            onChange={(e) => setData('cpf', digitsOnly(e.target.value).slice(0, 11))}
                                        />
                                        <InputError message={errors.cpf} />
                                    </div>
                                    <div className="grid gap-2">
                                        <Label htmlFor="birth_date">Data de nascimento</Label>
                                        <Input
                                            id="birth_date"
                                            type="date"
                                            value={data.birth_date}
                                            onChange={(e) => setData('birth_date', e.target.value)}
                                        />
                                        <InputError message={errors.birth_date} />
                                    </div>
                                    <div className="grid gap-2 md:col-span-2">
                                        <Label htmlFor="phone">Celular</Label>
                                        <Input id="phone" inputMode="tel" value={data.phone} onChange={(e) => setData('phone', e.target.value)} />
                                        <InputError message={errors.phone} />
                                    </div>
                                </div>
                            )}

                            {step === 1 && (
                                <div className="grid gap-5">
                                    <div className="flex items-start gap-4">
                                        <IdCard className="text-muted-foreground mt-1 size-6" />
                                        <div>
                                            <h2 className="text-lg font-semibold tracking-normal">Tipo de documento</h2>
                                            <p className="text-muted-foreground mt-1 text-sm leading-6">
                                                As imagens precisam estar legiveis, sem cortes, sem reflexo e com boa iluminacao.
                                            </p>
                                        </div>
                                    </div>
                                    <div className="grid max-w-sm gap-2">
                                        <Label>Documento</Label>
                                        <Select value={data.document_type} onValueChange={(value) => setData('document_type', value)}>
                                            <SelectTrigger>
                                                <SelectValue placeholder="Selecione" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="rg">RG</SelectItem>
                                                <SelectItem value="cnh">CNH</SelectItem>
                                            </SelectContent>
                                        </Select>
                                        <InputError message={errors.document_type} />
                                    </div>
                                </div>
                            )}

                            {step === 2 && (
                                <FileUploadField
                                    id="document_front"
                                    label="Frente do documento"
                                    file={data.document_front}
                                    error={errors.document_front}
                                    onChange={(file) => {
                                        clearErrors('document_front');
                                        setData('document_front', file);
                                    }}
                                />
                            )}

                            {step === 3 && (
                                <FileUploadField
                                    id="document_back"
                                    label="Verso do documento"
                                    file={data.document_back}
                                    error={errors.document_back}
                                    onChange={(file) => {
                                        clearErrors('document_back');
                                        setData('document_back', file);
                                    }}
                                />
                            )}

                            {step === 4 && (
                                <div className="grid gap-4">
                                    <div className="flex items-start gap-4">
                                        <FileImage className="text-muted-foreground mt-1 size-6" />
                                        <p className="text-muted-foreground text-sm leading-6">
                                            A selfie deve mostrar seu rosto e o documento ao lado, com boa iluminacao.
                                        </p>
                                    </div>
                                    <FileUploadField
                                        id="selfie"
                                        label="Selfie com documento"
                                        file={data.selfie}
                                        error={errors.selfie}
                                        onChange={(file) => {
                                            clearErrors('selfie');
                                            setData('selfie', file);
                                        }}
                                    />
                                </div>
                            )}

                            {step === 5 && (
                                <div className="grid gap-5">
                                    <div>
                                        <h2 className="text-lg font-semibold tracking-normal">Confirmacao</h2>
                                        <p className="text-muted-foreground mt-1 text-sm leading-6">
                                            Ao enviar, sua verificacao sera encaminhada para analise manual.
                                        </p>
                                    </div>
                                    <div className="grid gap-3">
                                        <label className="flex items-start gap-3 text-sm">
                                            <Checkbox
                                                checked={data.accept_terms}
                                                onCheckedChange={(checked) => setData('accept_terms', checked === true)}
                                            />
                                            <span>Li e aceito os termos de uso.</span>
                                        </label>
                                        <InputError message={errors.accept_terms} />
                                        <label className="flex items-start gap-3 text-sm">
                                            <Checkbox
                                                checked={data.accept_privacy}
                                                onCheckedChange={(checked) => setData('accept_privacy', checked === true)}
                                            />
                                            <span>Li e aceito a politica de privacidade.</span>
                                        </label>
                                        <InputError message={errors.accept_privacy} />
                                    </div>
                                </div>
                            )}

                            <div className="mt-8 flex flex-wrap items-center justify-between gap-3">
                                <Button
                                    type="button"
                                    variant="outline"
                                    disabled={step === 0 || processing}
                                    onClick={() => setStep((current) => Math.max(0, current - 1))}
                                >
                                    Voltar
                                </Button>
                                {step < 5 ? (
                                    <Button
                                        type="button"
                                        disabled={!canAdvance || processing}
                                        onClick={() => setStep((current) => Math.min(5, current + 1))}
                                    >
                                        Continuar
                                    </Button>
                                ) : (
                                    <Button type="submit" disabled={!canAdvance || processing}>
                                        {processing ? <LoaderCircle className="size-4 animate-spin" /> : <Send className="size-4" />}
                                        Enviar verificacao
                                    </Button>
                                )}
                            </div>
                        </section>
                    </form>
                ) : (
                    <section className="border-sidebar-border/70 rounded-md border p-5">
                        <h2 className="text-lg font-semibold tracking-normal">Acompanhamento</h2>
                        <p className="text-muted-foreground mt-2 text-sm leading-6">{currentStatus.message}</p>
                    </section>
                )}
            </div>
        </AppLayout>
    );
}
