import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { PaginatedData, Site, type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/react';
import { PlusIcon } from 'lucide-react';
import Heading from '../../components/heading';
import Pagination from '../../components/pagination';
import SitesTable from '../../components/tables/sites-table';
import { Button } from '../../components/ui/button';
import siteRoutes from '../../routes/sites';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: dashboard().url,
  },
  {
    title: 'Sites',
    href: siteRoutes.index().url,
  },
];

interface SitesIndexPageProps {
  sites: PaginatedData<Site>;
}

export default function SitesIndexPage({ sites }: SitesIndexPageProps) {
  const goToAddSitePage = () => {
    router.visit(siteRoutes.create().url);
  };
  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Sites" />
      <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <Heading title="Sites" description="Manage your websites" />

        <div className="flex w-full justify-end">
          <Button onClick={goToAddSitePage}>
            <PlusIcon />
            Add Site
          </Button>
        </div>

        <div className="rounded-xl border border-sidebar-border/70 bg-white dark:border-sidebar-border dark:bg-gray-800">
          <div className="py-6">
            <SitesTable sites={sites} />
          </div>
        </div>
        <Pagination links={sites.links} />
      </div>
    </AppLayout>
  );
}
