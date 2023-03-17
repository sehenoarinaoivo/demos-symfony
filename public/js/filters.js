window.onload = () => {
    const FiltersForm = document.querySelector("#filters");

    //On boucle sur les inputs
    document.querySelectorAll("#filters input").forEach(input => {
        input.addEventListener("change", () => {
            //Ici on intercèpte les clicks

            // On récupère les données du formulaire
            const Form = new FormData(FiltersForm);

            // On fabrique la 'queryString '
            const Params = new URLSearchParams();

            Form.forEach((value, key) => {
                Params.append(key, value);
                console.log('belo',key, value);
                console.log(Params.toString())
            })

            // On récupère l'url active
            const Url = new URL(window.location.href);
            console.log("idex",Url.pathname);

            // On lance la requête ajax
            fetch(Url.pathname + "?" + Params.toString() + "&ajax=1", {
                headers: {
                    "X-Requested-with": "XMLHttpRequest"
                }
            }).then(response => response.json())
            .then(data=> {
                const content = document.querySelector('#content');

                content.innerHTML = data.content;

                history.pushState({}, null, Url.pathname + "?" + Params.toString());
            }).catch(e => alert(e));
        });
    });
}