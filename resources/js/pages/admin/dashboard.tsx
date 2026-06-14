import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { ClipboardCheck } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Painel administrativo',
        href: '/admin',
    },
];

export default function AdminDashboard() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Painel administrativo" />
            <section className="flex h-full flex-1 flex-col gap-6 p-4">
                <div className="flex max-w-3xl flex-col gap-3">
                    <div className="bg-primary text-primary-foreground flex size-11 items-center justify-center rounded-md">
                        <ClipboardCheck className="size-5" />
                    </div>
                    <div>
                        <h1 className="text-foreground text-2xl font-semibold tracking-normal">Verificacoes pendentes</h1>
                        <p className="text-muted-foreground mt-2 text-sm leading-6">
                            Triagem inicial para analise manual, aprovacoes, rejeicoes, solicitacoes de correcao e bloqueios.
                        </p>
                    </div>
                </div>
            </section>
        </AppLayout>
    );
}
