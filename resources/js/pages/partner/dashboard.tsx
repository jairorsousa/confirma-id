import InputError from '@/components/input-error';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/react';
import { Building2, CheckCircle2, Clock3, History, LoaderCircle, Search, ShieldAlert, ShieldCheck } from 'lucide-react';
import { FormEventHandler } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Area do parceiro',
        href: '/partner',
    },
];

type Partner = {
    id: number;
    legal_name: string;
    trade_name: string | null;
    status: string;
    plan_name: string;
    can_query_cpf: boolean;
} | null;

type Stats = {
    total_queries: number;
    verified_users: number;
    monthly_queries: number;
};

type QueryResult = {
    verified: boolean;
    status: string;
    verification_code: string | null;
    name: string | null;
    document_masked: string | null;
    verified_at: string | null;
    expires_at: string | null;
} | null;

type QueryHistoryItem = {
    id: number;
    query_type: string;
    queried_term_masked: string | null;
    result: string;
    origin: string | null;
    created_at: string | null;
};

type QueryForm = {
    query_type: string;
    term: string;
};

const statusLabel: Record<string, string> = {
    active: 'Ativo',
    inactive: 'Inativo',
    blocked: 'Bloqueado',
    approved: 'Aprovado',
    under_review: 'Em analise',
    not_found: 'Nao encontrado',
};

const queryTypeLabel: Record<string, string> = {
    verification_code: 'Codigo',
    email: 'E-mail',
    cpf: 'CPF',
};

function formatDate(value: string | null) {
    if (!value) {
        return '-';
    }

    return new Intl.DateTimeFormat('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    }).format(new Date(value));
}

function resultTone(result: string): 'default' | 'secondary' | 'destructive' | 'outline' {
    if (result === 'approved') {
        return 'default';
    }

    if (result === 'blocked') {
        return 'destructive';
    }

    if (result === 'under_review') {
        return 'secondary';
    }

    return 'outline';
}

export default function PartnerDashboard({
    partner,
    stats,
    recent_queries,
    can_query,
    flash,
}: {
    partner: Partner;
    stats: Stats;
    recent_queries: QueryHistoryItem[];
    can_query: boolean;
    flash: {
        partner_query_result: QueryResult;
    };
}) {
    const { data, setData, post, processing, errors, reset } = useForm<QueryForm>({
        query_type: 'verification_code',
        term: '',
    });

    const submit: FormEventHandler = (event) => {
        event.preventDefault();

        post(route('partner.query'), {
            preserveScroll: true,
            onSuccess: () => reset('term'),
        });
    };

    const result = flash.partner_query_result;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Area do parceiro" />
            <div className="flex h-full flex-1 flex-col gap-6 p-4">
                <section className="border-sidebar-border/70 rounded-md border p-5">
                    <div className="flex flex-col gap-5 md:flex-row md:items-start md:justify-between">
                        <div className="flex max-w-3xl gap-4">
                            <div className="bg-primary text-primary-foreground flex size-11 shrink-0 items-center justify-center rounded-md">
                                <Building2 className="size-5" />
                            </div>
                            <div>
                                <div className="flex flex-wrap items-center gap-3">
                                    <h1 className="text-foreground text-2xl font-semibold tracking-normal">
                                        {partner?.trade_name || partner?.legal_name || 'Area do parceiro'}
                                    </h1>
                                    <Badge variant={can_query ? 'default' : 'destructive'}>
                                        {statusLabel[partner?.status ?? 'inactive'] ?? 'Inativo'}
                                    </Badge>
                                </div>
                                <div className="mt-3 flex flex-wrap gap-2">
                                    <Badge variant="outline">Plano {partner?.plan_name ?? '-'}</Badge>
                                    <Badge variant={partner?.can_query_cpf ? 'secondary' : 'outline'}>
                                        CPF {partner?.can_query_cpf ? 'permitido' : 'bloqueado'}
                                    </Badge>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section className="grid gap-3 md:grid-cols-3">
                    <div className="border-sidebar-border/70 rounded-md border p-4">
                        <div className="flex items-center justify-between">
                            <p className="text-muted-foreground text-sm">Consultas realizadas</p>
                            <History className="text-muted-foreground size-4" />
                        </div>
                        <strong className="mt-3 block text-2xl font-semibold">{stats.total_queries}</strong>
                    </div>
                    <div className="border-sidebar-border/70 rounded-md border p-4">
                        <div className="flex items-center justify-between">
                            <p className="text-muted-foreground text-sm">Usuarios verificados</p>
                            <ShieldCheck className="text-muted-foreground size-4" />
                        </div>
                        <strong className="mt-3 block text-2xl font-semibold">{stats.verified_users}</strong>
                    </div>
                    <div className="border-sidebar-border/70 rounded-md border p-4">
                        <div className="flex items-center justify-between">
                            <p className="text-muted-foreground text-sm">Consultas no mes</p>
                            <Clock3 className="text-muted-foreground size-4" />
                        </div>
                        <strong className="mt-3 block text-2xl font-semibold">{stats.monthly_queries}</strong>
                    </div>
                </section>

                <section className="grid gap-6 lg:grid-cols-[minmax(0,420px)_1fr]">
                    <form onSubmit={submit} className="border-sidebar-border/70 rounded-md border p-5">
                        <div className="flex items-center gap-3">
                            <div className="bg-primary text-primary-foreground flex size-9 items-center justify-center rounded-md">
                                <Search className="size-4" />
                            </div>
                            <h2 className="text-lg font-semibold">Consulta de identidade</h2>
                        </div>

                        <div className="mt-5 grid gap-4">
                            <div className="grid gap-2">
                                <Label htmlFor="query_type">Tipo</Label>
                                <Select value={data.query_type} onValueChange={(value) => setData('query_type', value)}>
                                    <SelectTrigger id="query_type">
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="verification_code">Codigo ConfirmaID</SelectItem>
                                        <SelectItem value="email">E-mail</SelectItem>
                                        <SelectItem value="cpf" disabled={!partner?.can_query_cpf}>
                                            CPF
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="term">Termo</Label>
                                <Input
                                    id="term"
                                    value={data.term}
                                    disabled={!can_query}
                                    onChange={(event) => setData('term', event.target.value)}
                                    placeholder={
                                        data.query_type === 'verification_code'
                                            ? 'CID-000000'
                                            : data.query_type === 'cpf'
                                              ? '000.000.000-00'
                                              : 'pessoa@email.com'
                                    }
                                />
                                <InputError message={errors.term} />
                                <InputError message={errors.query_type} />
                            </div>

                            <Button type="submit" disabled={!can_query || processing}>
                                {processing && <LoaderCircle className="size-4 animate-spin" />}
                                Consultar
                            </Button>
                        </div>
                    </form>

                    <div className="border-sidebar-border/70 rounded-md border p-5">
                        <div className="flex items-center gap-3">
                            <div className="bg-muted flex size-9 items-center justify-center rounded-md">
                                {result?.verified ? <CheckCircle2 className="size-4" /> : <ShieldAlert className="size-4" />}
                            </div>
                            <h2 className="text-lg font-semibold">Resultado</h2>
                        </div>

                        {result ? (
                            <div className="mt-5 grid gap-4 md:grid-cols-2">
                                <div>
                                    <p className="text-muted-foreground text-xs uppercase">Status</p>
                                    <Badge className="mt-2" variant={resultTone(result.status)}>
                                        {statusLabel[result.status] ?? result.status}
                                    </Badge>
                                </div>
                                <div>
                                    <p className="text-muted-foreground text-xs uppercase">Codigo</p>
                                    <p className="mt-2 font-medium">{result.verification_code ?? '-'}</p>
                                </div>
                                <div>
                                    <p className="text-muted-foreground text-xs uppercase">Nome</p>
                                    <p className="mt-2 font-medium">{result.name ?? '-'}</p>
                                </div>
                                <div>
                                    <p className="text-muted-foreground text-xs uppercase">Documento</p>
                                    <p className="mt-2 font-medium">{result.document_masked ?? '-'}</p>
                                </div>
                                <div>
                                    <p className="text-muted-foreground text-xs uppercase">Verificado em</p>
                                    <p className="mt-2 font-medium">{formatDate(result.verified_at)}</p>
                                </div>
                                <div>
                                    <p className="text-muted-foreground text-xs uppercase">Expira em</p>
                                    <p className="mt-2 font-medium">{formatDate(result.expires_at)}</p>
                                </div>
                            </div>
                        ) : (
                            <p className="text-muted-foreground mt-5 text-sm">Nenhuma consulta realizada nesta sessao.</p>
                        )}
                    </div>
                </section>

                <section className="border-sidebar-border/70 rounded-md border">
                    <div className="border-sidebar-border/70 flex items-center justify-between border-b p-4">
                        <h2 className="font-semibold">Historico recente</h2>
                    </div>
                    <div className="overflow-x-auto">
                        <table className="w-full text-sm">
                            <thead>
                                <tr className="text-muted-foreground border-sidebar-border/70 border-b text-left">
                                    <th className="px-4 py-3 font-medium">Tipo</th>
                                    <th className="px-4 py-3 font-medium">Termo</th>
                                    <th className="px-4 py-3 font-medium">Resultado</th>
                                    <th className="px-4 py-3 font-medium">Origem</th>
                                    <th className="px-4 py-3 font-medium">Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                {recent_queries.length > 0 ? (
                                    recent_queries.map((item) => (
                                        <tr key={item.id} className="border-sidebar-border/70 border-b last:border-0">
                                            <td className="px-4 py-3">{queryTypeLabel[item.query_type] ?? item.query_type}</td>
                                            <td className="px-4 py-3">{item.queried_term_masked ?? '-'}</td>
                                            <td className="px-4 py-3">
                                                <Badge variant={resultTone(item.result)}>{statusLabel[item.result] ?? item.result}</Badge>
                                            </td>
                                            <td className="px-4 py-3">{item.origin ?? '-'}</td>
                                            <td className="px-4 py-3">{formatDate(item.created_at)}</td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td className="text-muted-foreground px-4 py-6" colSpan={5}>
                                            Nenhuma consulta registrada.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </AppLayout>
    );
}
