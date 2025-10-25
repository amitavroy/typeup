import { router } from '@inertiajs/react';
import { formatDate } from '../../lib/utils';
import siteRoutes from '../../routes/sites';
import { PaginatedData, Site } from '../../types';
import {
  Table,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '../ui/table';

interface SitesTableProps {
  sites: PaginatedData<Site>;
}

export default function SitesTable({ sites }: SitesTableProps) {
  return (
    <Table>
      <TableHeader>
        <TableRow>
          <TableHead>#</TableHead>
          <TableHead>Name</TableHead>
          <TableHead>Domain</TableHead>
          <TableHead>Site Key</TableHead>
          <TableHead>Created At</TableHead>
        </TableRow>
        {sites.data.map((site) => (
          <TableRow
            key={site.id}
            className="cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800"
            onClick={() => router.visit(siteRoutes.show(site.id).url)}
          >
            <TableCell>{site.id}</TableCell>
            <TableCell>{site.name}</TableCell>
            <TableCell>{site.domain}</TableCell>
            <TableCell>{site.site_key}</TableCell>
            <TableCell>{formatDate(site.created_at)}</TableCell>
          </TableRow>
        ))}
      </TableHeader>
    </Table>
  );
}
