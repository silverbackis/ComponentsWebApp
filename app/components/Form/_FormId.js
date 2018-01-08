export function getFormId (formVars) {
  if (formVars.vars) {
    formVars = formVars.vars
  }
  return 'form_' + (formVars.id || formVars.attr.id)
}
