function utf8(value) {
  return new TextEncoder().encode(value);
}

async function importSecret(secret) {
  return crypto.subtle.importKey(
    'raw',
    utf8(secret),
    { name: 'HMAC', hash: 'SHA-256' },
    false,
    ['sign']
  );
}

export async function signPayload(body, timestamp, secret) {
  const key = await importSecret(secret);
  const signature = await crypto.subtle.sign('HMAC', key, utf8(`${timestamp}|${body}`));

  return Array.from(new Uint8Array(signature))
    .map((byte) => byte.toString(16).padStart(2, '0'))
    .join('');
}
