/**
 * @author Cristian Camilo Vasquez Osorio 16-12-20
 */
class Categories
{

    constructor()
    {
        this.route = '/categories';
        this.registerCategories = document.getElementById('registrarCategorias');
        this.code;
        this.name;
        this.description;
    }

    categories()
    {
        this.registerCategories.addEventListener('submit', event => {
            event.preventDefault();
            this.code        = document.getElementById('codigo').value;
            this.name        = document.getElementById('nombre').value;
            this.description = document.getElementById('descripcion').value;
            const data = {
                'code'        : this.code,
                'name'        : this.name,
                'description' : this.description,
                'active'      : true
            }
            this.fetch('POST', this.route, data).then(consumible => {
                if (!consumible.error) {
                    alert(consumible.message);
                    location.reload();
                }
            });
        })
        return this;
    }

    disableCategories()
    {
        let elements = document.getElementsByClassName("desactivar");
        for (let index = 0; index < elements.length; index++) {
            elements[index].addEventListener('click', () => {
                let id = elements[index].getAttribute("delete");
                this.fetch('PUT', this.route, `/${id}`).then(consumible => {
                    if (!consumible.error) {
                        alert(consumible.message);
                        location.reload();
                    } 
                })
            })
        }
    }

    fetch(type, route, data)
    {
        if (type === 'GET') {
            return fetch(route, {
                'method' : 'GET'
            }).then(response => {
                return response.json();
            });
        } else if (type === 'POST') {
            return fetch(route, {
                'method' : 'POST',
                'body'   : JSON.stringify(data)
            }).then(response => {
                return response.json();
            });
        } else if (type === 'PUT') {
            return fetch(route + data, {
                'method' : 'PUT'
            }).then(response => {
                return response.json();
            });
        }
    }
}
(new Categories()).categories().disableCategories();
