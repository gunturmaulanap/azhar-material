// utils/storageUrl.ts
export const toPublicUrl = (p?: string | null): string | undefined => {
  if (!p) return undefined;
  if (/^https?:\/\//i.test(p)) return p;

  // normalisasi
  let s = String(p).trim();
  s = s.replace(/^\/+/, ""); // hapus leading slash

  // kalau sudah "storage/..." biarkan jadi "/storage/..."
  if (/^storage\//i.test(s)) return `/${s}`;

  // hapus prefix "public/"
  s = s.replace(/^public\//i, "");

  // kasus lama: kadang disimpan "public/storage/...."
  s = s.replace(/^public\/storage\//i, "storage/");

  // default: arahkan ke /storage
  return `/storage/${s}`;
};
