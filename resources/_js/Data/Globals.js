export const slugify = function (strVal) {
   return strVal
      .trim()
      .toLowerCase()
      .replace(/ /g, '-')
      .replace(/[-]+/g, '-')
      .replace(/![a-z0-9]+/g, '')
      .replace(/[\-]$/g, '')
      .replace(/[\-]+$/g, '')
      .replace(/^[\-]/g, '')
      .replace(/^[\-]+/g, '')
      .replace(/[^\w-]+/g, '')
}
