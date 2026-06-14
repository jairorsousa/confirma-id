import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { SearchCheck } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Area do parceiro',
        href: '/partner',
    },
];

export default function PartnerDashboard() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Area do parceiro" />
            <section className="flex h-full flex-1 flex-col gap-6 p-4">
                <div className="flex max-w-3xl flex-col gap-3">
                    <div className="bg-primary text-primary-foreground flex size-11 items-center justify-center rounded-md">
                        <SearchCheck className="size-5" />
                    </div>
                    <div>
                        <h1 className="text-foreground text-2xl font-semibold tracking-normal">Area do parceiro</h1>
                        <p className="text-muted-foreground mt-2 text-sm leading-6">
                            Consulte o status de verificacao de usuarios sem acessar documentos, selfies ou dados sensiveis.
                        </p>
                    </div>
                </div>
            </section>
        </AppLayout>
    );
}
