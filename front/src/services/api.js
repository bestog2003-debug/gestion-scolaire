// Client HTTP minimal : envoie les cookies de session et transforme les erreurs API en Error lisibles.
export async function apiRequest(path, { method = 'GET', body } = {}) {
  const response = await fetch(`/api${path}`, {
    method,
    credentials: 'include',
    headers: body ? { 'Content-Type': 'application/json' } : {},
    body: body ? JSON.stringify(body) : undefined,
  })
  let payload
  try {
    payload = await response.json()
  } catch {
    throw new Error("Réponse invalide : l'API PHP est-elle bien lancée ?")
  }
  if (!response.ok) throw new Error(payload.error || 'Erreur serveur')
  return payload
}
