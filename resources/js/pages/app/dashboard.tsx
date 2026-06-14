import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { ShieldCheck } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Area do usuario',
        href: '/app',
    },
];

export default function UserDashboard() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Area do usuario" />
            <section className="flex h-full flex-1 flex-col gap-6 p-4">
                <div className="flex max-w-3xl flex-col gap-3">
                    <div className="bg-primary text-primary-foreground flex size-11 items-center justify-center rounded-md">
                        <ShieldCheck className="size-5" />
                    </div>
                    <div>
                        <h1 className="text-foreground text-2xl font-semibold tracking-normal">Area do usuario</h1>
                        <p className="text-muted-foreground mt-2 text-sm leading-6">
                            Acompanhe sua verificacao de identidade e envie as informacoes necessarias para receber seu ConfirmaID.
                        </p>
                    </div>
                </div>
            </section>
        </AppLayout>
    );
}
