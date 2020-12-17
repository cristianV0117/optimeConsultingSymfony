/**
 * @author Cristian Camilo Vasquez Osorio 16-12-20
 */
class Products
{

    constructor()
    {
        this.route = '/products';
        this.registerProducts = document.getElementById('registrarProductos');
        this.code;
        this.name;
        this.description;
        this.brand;
        this.categorie;
        this.price;
    }

    products()
    {
        this.registerProducts.addEventListener('submit', event => {
            event.preventDefault();
            this.code        = document.getElementById('codigo').value;
            this.name        = document.getElementById('nombre').value;
            this.description = document.getElementById('descripcion').value;
            this.brand       = document.getElementById('marca').value;
            this.categorie   = document.getElementById('categorias').value;
            this.price       = document.getElementById('precio').value;
            const data = {
                'code'        : this.code,
                'name'        : this.name,
                'description' : this.description,
                'brand'       : this.brand,
                'categorie'   : this.categorie,
                'price'       : this.price
            }
            this.fetch('POST', this.route, data).then(consumible => {
                if (!consumible.error) {
                    alert(consumible.message);
                    location.reload();
                } else {
                    alert(consumible.message);
                }
            });
        })
        return this;
    }

    deleteProducts()
    {
        let con;
        let elements = document.getElementsByClassName("eliminar");
        for (let index = 0; index < elements.length; index++) {
            elements[index].addEventListener('click', () => {
                con = confirm("¿ Esta seguro de borrar el registro ?");
                if (con) {
                    let id = elements[index].getAttribute("delete");
                    this.fetch('DELETE', this.route, `/${id}`).then(consumible => {
                        if (!consumible.error) {
                            alert(consumible.message);
                            location.reload();
                        }
                    });
                }
            })
        }
    }

    categories()
    {
        let categories = document.getElementById("categorias");
        this.fetch('GET', '/categories/select').then(consumible => {
            if (!consumible.error) {
                for (let index = 0; index < consumible.message.length; index++) {
                    categories.innerHTML += `<option value="${consumible.message[index].id}" >${consumible.message[index].name}</option>`;
                }
            }
        });
        return this;
    }

    fetch(type, route, data = null)
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
        } else {
            return fetch(route + data, {
                'method' : 'DELETE'
            }).then(response => {
                return response.json();
            });
        }
    }
}
(new Products()).categories().products().deleteProducts();