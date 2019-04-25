import React from 'react';
import { List, Datagrid, TextField, EmailField, DateField, ShowButton, EditButton } from 'react-admin';

export const UserList = (props) => (
  <List {...props} title="Users">
    <Datagrid>
      <TextField source="originId" label="ID"/>
      <EmailField source="email" label="Email" />
      <TextField source="name" label="Name"/>
      <TextField source="phone" label="Phone"/>
      <DateField source="createdAt" label="Date"/>
      <ShowButton />
      <EditButton />
    </Datagrid>
  </List>
);
