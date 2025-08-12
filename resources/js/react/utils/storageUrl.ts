// utils/storageUrl.ts
export const toPublicUrl = (p?: string | null): string | undefined => {
  if (!p) return undefined; // <-- tidak pernah return null
  if (/^https?:\/\//i.test(p)) return p;
  const clean = String(p).replace(/^public\//i, "");
  return `/storage/${clean}`;
};
