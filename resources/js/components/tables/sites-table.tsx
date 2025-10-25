import {
  Table,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '../ui/table';

export default function SitesTable() {
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
        <TableRow>
          <TableCell>1</TableCell>
          <TableCell>Site 1</TableCell>
          <TableCell>https://site1.com</TableCell>
          <TableCell>site1</TableCell>
          <TableCell>2021-01-01</TableCell>
        </TableRow>
      </TableHeader>
    </Table>
  );
}
