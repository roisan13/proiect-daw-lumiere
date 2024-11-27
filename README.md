# Cinematograful Lumiere (Proiect DAW)

## Descriere

**Lumiere** este o aplicație web dedicată gestionării unui cinematograf modern, care oferă utilizatorilor o experiență completă de navigare și rezervare. Platforma este construită pentru a permite vizualizarea filmelor disponibile, programelor de rulare, rezervarea locurilor și achiziționarea biletelor pentru spectacolele de cinema. În viitor, platforma va oferi și posibilitatea de a lăsa recenzii pentru filmele vizionate.

## Caracteristici principale

- **Catalog de filme**: Utilizatorii pot vizualiza lista completă de filme disponibile în cinematograf, inclusiv informații despre fiecare film.
- **Programări de rulare**: Afișează datele și orele la care sunt proiectate filmele, împreună cu locațiile în sălile cinematografului.
- **Locuri disponibile**: Utilizatorii pot vedea locurile disponibile pentru fiecare ecranizare și pot selecta locurile dorite.
- **Rezervare și achiziționare bilete**: Sistemul permite rezervarea locurilor și achiziționarea de bilete direct din platformă.
- **Sugestii filme**: Utilizatorul poate adăuga un film în catalog, ca sugestie pentru cinematograf.
- **Modificare rezervari**: Posibilitatea de a edita un booking actual, sau de a renunța complet la el.

## Descrierea arhitecturii aplicației

Aplicația web a cinematografului este construită folosind arhitectura **Model-View-Controller (MVC)**, cu trei componente principale:

- **Model**: Reprezintă logica de business și interacționează cu baza de date pentru a manipula datele aplicației.
- **View**: Reprezintă interfața utilizatorului și este responsabilă pentru afisarea datelor.
- **Controller**: Gestionează fluxul de date dintre model și view, preluând acțiunile utilizatorului și actualizând modelele sau vizualizările corespunzătoare.

### Roluri și entități

### Componentele principale ale aplicației

1. **Front-end**:
   - HTML: Structura paginilor web.
   - CSS (Picnic CSS framework): Design și stilizare ușoară a interfeței utilizatorului.

2. **Back-end**:
   - PHP: Logica de procesare a cererilor și gestionarea interacțiunii cu baza de date.
   - MVC: Patternul folosit pentru a organiza aplicația într-un mod structurat (Model, View, Controller).

3. **Baza de date**:
   - **MySQL**: Sistemul de gestionare a bazelor de date folosit pentru stocarea informațiilor despre filme, utilizatori, rezervări și altele.
   - Structura bazei de date include tabele precum `movies`, `screenings`, `users`, `bookings`, `cinema_halls`, etc.

### Descrierea bazei de date

Baza de date este formată din mai multe tabele interconectate, fiecare având o funcție specifică în cadrul aplicației:

- **users**: Stochează informațiile despre utilizatori (nume, email, parolă, rol).
- **movies**: Conține informații despre filme (titlu, rating, descriere, poster).
- **screenings**: Reprezintă programările filmelor, inclusiv data, ora, sala și locurile disponibile.
- **cinema_halls**: Detalii despre sălile de cinema din cadrul cinematografului.
- **bookings**(pe viitor): Permite stocarea rezervărilor făcute de utilizatori pentru un anumit screening.

### Relațiile între entități

- **Un film** poate avea multiple **screenings** (programări de rulare).
- Fiecare **screening** are un loc în o **cinema hall** specifică.
- **Users** pot face **bookings** pentru **screenings** specifice.
- Fiecare **booking** este legat de un **user** și un **screening**.
  
Aceste relații sunt implementate în baza de date prin utilizarea de chei externe (foreign keys), iar integritatea datelor este asigurată prin constrângeri precum `ON DELETE RESTRICT` și `ON UPDATE CASCADE`.


## URL-uri
[/users/index](http://lumiere.infy.uk/proiect_daw_lumiere/users/index)  
[/movies/index](http://lumiere.infy.uk/proiect_daw_lumiere/movies/index)  
[/screenings/index](http://lumiere.infy.uk/proiect_daw_lumiere/screenings/index)  
[/screenings/today](http://lumiere.infy.uk/proiect_daw_lumiere/screenings/today)  
[/users/login](http://lumiere.infy.uk/proiect_daw_lumiere/users/login)  

### Notă

Acest proiect este în curs de dezvoltare activă, iar nu toate funcționalitațile sunt încă valabile.
