import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { Head } from '@inertiajs/react';
import SiteForm from '../../components/forms/sites-form';
import Heading from '../../components/heading';
import sites from '../../routes/sites';
import { BreadcrumbItem, Site } from '../../types';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: dashboard().url,
  },
  {
    title: 'Sites',
    href: sites.index().url,
  },
  {
    title: 'Create',
    href: sites.create().url,
  },
];

interface SitesCreatePageProps {
  site: Site;
}

export default function SitesCreatePage({ site }: SitesCreatePageProps) {
  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Sites" />
      <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <Heading title="Create Site" description="Create a new site" />

        <div className="flex flex-1 items-start justify-start">
          <div className="w-full max-w-2xl rounded-xl bg-white sm:w-full dark:bg-gray-800">
            <SiteForm site={site} />
          </div>
        </div>
      </div>
    </AppLayout>
  );
}
