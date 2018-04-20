export const CookiesToHeaders = (cookies) => {
  return {
    'X-XSRF-TOKEN': cookies['XSRF-TOKEN'] || '',
    'Cookie': 'PHPSESSID=' + cookies['PHPSESSID'] || ''
  }
}
export default CookiesToHeaders
