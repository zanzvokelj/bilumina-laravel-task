Uporabljena tehnologija
	•	PHP 8.4
	•	Laravel 11
	•	TailwindCSS
	•	Vite
	•	Laravel HTTP Client
	•	Herd (lokalno razvojno okolje)

⸻

Arhitektura in pristop

1. MVC struktura

Aplikacija sledi Laravel MVC arhitekturi:
	•	Route definira vhodno točko (/)
	•	ProductController skrbi za:
	•	pridobivanje podatkov iz API-ja
	•	izbor skupine
	•	transformacijo podatkov
	•	sortiranje
	•	Blade view skrbi izključno za prikaz podatkov

Logika ni pomešana z izpisom HTML, temveč je ločena po odgovornostih.

⸻

2. Pridobivanje podatkov

Podatki se pridobivajo iz:

https://egi.bilumina.com/mw/api/v1/items/get

API ključ ni hardcoded, temveč je shranjen v .env datoteki.

Do njega se dostopa preko config/services.php, kar omogoča varno in produkcijsko pripravljeno rešitev.

⸻

3. Transformacija podatkov

API vrača artikle kot objekt (Record<string, Item>), zato se:
	•	izbere ciljna skupina (ID 30284 – Košare)
	•	podatki pretvorijo v tabelo
	•	pripravijo v enostavno strukturo za view
	•	povežejo s pravilno CDN potjo za slike

S tem view ni neposredno vezan na celotno API strukturo.

⸻

4. Sortiranje

Sortiranje po ceni je izvedeno na backendu:
	•	privzeto (API vrstni red)
	•	naraščajoče
	•	padajoče

Sort parameter se prenaša preko GET query (?sort=asc ali ?sort=desc).

⸻

5. Robni primeri (edge cases)

Upoštevana so naslednja stanja:
	•	prazen rezultat (empty state)
	•	izpis napake pri neuspešnem API klicu
	•	označevanje izdelkov brez zaloge (stock == 0)


## Zagon projekta (Laravel)

1. Namesti PHP odvisnosti:

composer install

2. Namesti Node odvisnosti:

npm install

3. Kopiraj `.env` datoteko:

cp .env.example .env

4. Generiraj APP_KEY:

php artisan key:generate

5. Zaženi Vite (Tailwind build):

npm run dev

6. V drugem terminalu zaženi Laravel strežnik:

php artisan serve