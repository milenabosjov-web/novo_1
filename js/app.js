const tabelaTelo = document.getElementById('tabela-telo');
const praznaLista = document.getElementById('prazna-lista');
const formaZadatak = document.getElementById('forma-zadatak');
const poljeGreska = document.getElementById('zadatak-greska');

async function ucitajZadatke() {
  const odgovor = await fetch('api/zadaci.php');
  const podaci = await odgovor.json();

  if (!podaci.success) return;

  tabelaTelo.innerHTML = '';
  podaci.zadaci.forEach(dodajRedUTabelu);
  osveziPraznuPoruku();
}

function osveziPraznuPoruku() {
  praznaLista.hidden = tabelaTelo.children.length > 0;
}

function dodajRedUTabelu(zadatak) {
  const red = document.createElement('tr');

  const statusKlasa = zadatak.zavrsen ? 'status-zavrsen' : 'status-neaktivan';
  const statusTekst = zadatak.zavrsen ? 'Završen' : 'U toku';

  red.innerHTML = `
    <td>${escapeHtml(zadatak.naziv)}</td>
    <td>${zadatak.rok}</td>
    <td class="${statusKlasa}">${statusTekst}</td>
    <td>
      <input type="hidden" class="zadatak-id" value="${zadatak.id}">
      <button class="btn-zavrsi">${zadatak.zavrsen ? 'Vrati' : 'Završi'}</button>
      <button class="btn-obrisi">Obriši</button>
    </td>
  `;

  red.querySelector('.btn-zavrsi').addEventListener('click', () => promeniStatus(zadatak.id));
  red.querySelector('.btn-obrisi').addEventListener('click', () => obrisiZadatak(zadatak.id));

  tabelaTelo.appendChild(red);
}

function escapeHtml(tekst) {
  const div = document.createElement('div');
  div.textContent = tekst;
  return div.innerHTML;
}

formaZadatak.addEventListener('submit', async (e) => {
  e.preventDefault();
  poljeGreska.textContent = '';

  const naziv = document.getElementById('naziv').value.trim();
  const rok = document.getElementById('rok').value;

  if (!naziv) {
    poljeGreska.textContent = 'Naziv zadatka je obavezan.';
    return;
  }

  const odgovor = await fetch('api/zadaci.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ naziv, rok })
  });
  const podaci = await odgovor.json();

  if (podaci.success) {
    dodajRedUTabelu(podaci.zadatak);
    osveziPraznuPoruku();
    formaZadatak.reset();
  } else {
    poljeGreska.textContent = podaci.error || 'Greška pri dodavanju zadatka.';
  }
});

async function promeniStatus(id) {
  const odgovor = await fetch('api/zadaci.php', {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id })
  });
  const podaci = await odgovor.json();
  if (podaci.success) ucitajZadatke();
  else alert(podaci.error);
}

async function obrisiZadatak(id) {
  if (!confirm('Da li ste sigurni da želite da obrišete zadatak?')) return;

  const odgovor = await fetch('api/zadaci.php', {
    method: 'DELETE',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ id })
  });
  const podaci = await odgovor.json();
  if (podaci.success) ucitajZadatke();
  else alert(podaci.error);
}

ucitajZadatke();
