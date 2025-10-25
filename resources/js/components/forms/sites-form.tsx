import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import {
  Field,
  FieldContent,
  FieldDescription,
  FieldError,
  FieldGroup,
  FieldLabel,
  FieldLegend,
  FieldSet,
} from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import { Site } from '@/types';
import { router, useForm } from '@inertiajs/react';
import { destroy, store, update } from '../../routes/sites';
import { ConfirmDialog } from '../confirm-dialog';

export default function SiteForm({ site }: { site: Site }) {
  const isEdit = site.id !== undefined;
  const { data, setData, post, put, processing, errors, reset } = useForm({
    name: site.name ? site.name : '',
    domain: site.domain ? site.domain : '',
    site_key: site.site_key ? site.site_key : '',
  });
  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    const url = isEdit ? update(site.id).url : store().url;
    if (isEdit) {
      put(url);
    } else {
      post(url, {
        onSuccess: () => {
          reset();
        },
      });
    }
  };

  const handleDelete = () => router.delete(destroy(site.id).url);
  return (
    <Card>
      <CardContent>
        <form onSubmit={handleSubmit}>
          <FieldSet>
            <FieldLegend>Add a new Site</FieldLegend>
            <FieldDescription>Add details about a new site</FieldDescription>
            <FieldGroup>
              {/* Category name */}
              <Field>
                <FieldLabel htmlFor="name">Site name</FieldLabel>
                <FieldContent>
                  <Input
                    id="name"
                    type="text"
                    value={data.name}
                    onChange={(e) => setData('name', e.target.value)}
                    placeholder="Enter the site name"
                  />
                  <FieldError
                    errors={
                      errors.name
                        ? [
                            {
                              message: errors.name,
                            },
                          ]
                        : undefined
                    }
                  />
                </FieldContent>
              </Field>

              {/* Site domain */}
              <Field>
                <FieldLabel htmlFor="domain">Site domain</FieldLabel>
                <FieldContent>
                  <Input
                    id="domain"
                    type="text"
                    value={data.domain}
                    onChange={(e) => setData('domain', e.target.value)}
                    placeholder="Enter the site domain"
                  />
                  <FieldError
                    errors={
                      errors.domain
                        ? [
                            {
                              message: errors.domain,
                            },
                          ]
                        : undefined
                    }
                  />
                </FieldContent>
              </Field>
            </FieldGroup>

            {/* Submit Button */}
            <div className="flex justify-between gap-2">
              {isEdit && (
                <ConfirmDialog
                  title="Delete Site"
                  description="Are you sure you want to delete this site?"
                  confirmButtonText="Delete"
                  trigger={
                    <Button variant="destructive" type="button">
                      Delete
                    </Button>
                  }
                  onConfirm={handleDelete}
                />
              )}
              <Button type="submit" disabled={processing}>
                {processing ? 'Saving...' : isEdit ? 'Save Site' : 'Add Site'}
              </Button>
            </div>
          </FieldSet>
        </form>
      </CardContent>
    </Card>
  );
}
