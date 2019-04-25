import React from 'react';
import { Show, SimpleShowLayout, TextField, DateField, EmailField, EditButton } from 'react-admin';

export const UserShow = (props) => (
  <Show { ...props }>
    <SimpleShowLayout>
      <TextField source="originId" label="ID"/>
      <EmailField source="email" label="Email" />
      <TextField source="name" label="Name"/>
      <TextField source="phone" label="Phone"/>
      <DateField source="createdAt" label="Date"/>
      <EditButton />
    </SimpleShowLayout>
  </Show>
);
