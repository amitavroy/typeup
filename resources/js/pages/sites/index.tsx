import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import SitesTable from '../../components/tables/sites-table';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: dashboard().url,
  },
  {
    title: 'Sites',
    href: '/sites',
  },
];

export default function SitesIndex() {
  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Sites" />
      <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-2xl font-semibold text-gray-900 dark:text-gray-100">
              Sites
            </h1>
            <p className="text-sm text-gray-600 dark:text-gray-400">
              Manage your websites
            </p>
          </div>
        </div>

        <div className="flex-1 rounded-xl border border-sidebar-border/70 bg-white dark:border-sidebar-border dark:bg-gray-800">
          <div className="py-6">
            <SitesTable />
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
