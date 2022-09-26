const { createApp } = Vue

createApp({
    data() {
        return {
            search: "",
            contactos: []
        }
    },
    mounted() {
        this.obtenerContactos();
        this.buscarContactos();
    },
    methods: {
        obtenerContactos() {
            fetch('http://localhost:80/contactos/', {
                headers: {
                    'Content-type': 'application/json'
                },
                method: 'GET',
                body: JSON.stringify()
            })
                .then(function (response) {
                    // Transforma la respuesta. En este caso lo convierte a JSON
                    return response.json();
                })
                .then((json) => {
                    // Usamos la información recibida como necesitemos
                    console.log(json)
                    this.contactos = json
                });
        },
        buscarContactos() {
            if (this.search !== "") {
                fetch('http://localhost:80/contactos/buscar/?nombre='.concat(this.search), {
                    headers: {
                        'Content-type': 'application/json'
                    },
                    method: 'GET',
                    body: JSON.stringify()
                })
                    .then(function (response) {
                        // Transforma la respuesta. En este caso lo convierte a JSON
                        return response.json();
                    })
                    .then((json) => {
                        // Usamos la información recibida como necesitemos
                        console.log(json)
                        this.contactos = json
                    });
            } else {
                this.obtenerContactos()
            }
        }
    },
}).mount('#app')