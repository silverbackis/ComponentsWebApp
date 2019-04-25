import React from 'react';
import { Edit, SimpleForm, DisabledInput, TextInput, email, ArrayInput, SimpleFormIterator, DateInput } from 'react-admin';

export const UserEdit = (props) => (
  <Edit {...props}>
    <SimpleForm>
      <DisabledInput source="originId" label="ID"/>
      <TextInput source="email" label="Email" validate={ email() } />
      <TextInput source="name" label="Name"/>
      <TextInput source="phone" label="Phone"/>
      <ArrayInput source="roles" label="Roles">
        <SimpleFormIterator>
          <TextInput />
        </SimpleFormIterator>
      </ArrayInput>
      <DateInput disabled source="createdAt" label="Date"/>
    </SimpleForm>
  </Edit>
);
