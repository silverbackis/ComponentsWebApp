import React from "react";
import { ArrayInput, SimpleFormIterator, SelectInput, required } from 'react-admin';
import {
  EditGuesser,
  InputGuesser,
  ListGuesser,
  FieldGuesser,
  ShowGuesser,
  CreateGuesser
} from "@api-platform/admin";

const userRoles = [
  { id: 'ROLE_USER', name: 'ROLE_USER' },
  { id: 'ROLE_ADMIN', name: 'ROLE_ADMIN' },
  { id: 'ROLE_SUPER_ADMIN', name: 'ROLE_SUPER_ADMIN' },
]

export const UserEdit = props => (
  <EditGuesser {...props}>
    <InputGuesser source="enabled" />
    <InputGuesser source="username" />
    <ArrayInput {...props} source="roles">
      <SimpleFormIterator>
        <SelectInput defaultValue="ROLE_USER" choices={userRoles} validate={required()} />
      </SimpleFormIterator>
    </ArrayInput>
    <InputGuesser source="plainPassword" label="New password" />
  </EditGuesser>
);

export const UserList = props => (
  <ListGuesser {...props}>
    <FieldGuesser source={"username"} />
    <FieldGuesser source={"enabled"} />
  </ListGuesser>
);

export const UserShow = props => (
  <ShowGuesser {...props}>
    <FieldGuesser source={"enabled"} addLabel={true} />
    <FieldGuesser source={"username"} addLabel={true} />
    <FieldGuesser source={"roles"} addLabel={true} />
  </ShowGuesser>
);

export const UserCreate = props => (
  <CreateGuesser {...props}>
    <InputGuesser source="enabled" />
    <InputGuesser source="username" />
    <ArrayInput {...props} source="roles">
      <SimpleFormIterator>
        <SelectInput defaultValue="ROLE_USER" choices={userRoles} validate={required()} />
      </SimpleFormIterator>
    </ArrayInput>
    <InputGuesser source="plainPassword" label="New password" validate={required()} />
  </CreateGuesser>
);
