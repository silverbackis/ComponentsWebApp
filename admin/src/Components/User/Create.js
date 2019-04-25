import React from 'react';
import { Create, SimpleForm, TextInput, email, required, ArrayInput, SimpleFormIterator } from 'react-admin';

export const UserCreate = (props) => (
  <Create { ...props }>
    <SimpleForm>
      <TextInput source="email" label="Email" validate={ email() } />
      <TextInput source="plainPassword" label="Password" validate={ required() } />
      <TextInput source="name" label="Name"/>
      <TextInput source="phone" label="Phone"/>
      <ArrayInput source="roles" label="Roles">
        <SimpleFormIterator>
          <TextInput />
        </SimpleFormIterator>
      </ArrayInput>
    </SimpleForm>
  </Create>
);
