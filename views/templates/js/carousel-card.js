// =================== CAMBIO DE CAJAS EN LA SECCION DE HORARIOS ==========================
const carreraSelectSuperior = document.getElementById('carreraSelect');
const carreraSelectInferior = document.getElementById('carrera_carrera_id');
const usuarioSelect = document.getElementById("usuario_usuario_id");
const inputNumeroEmpleado = document.getElementById("numero_empleado");

document.addEventListener('DOMContentLoaded', () => {
    console.log("ID Usuario:", idusuario);
    console.log("Tipo Usuario:", tipoUsuarioId);
    console.log("Carrera ID:", carreraId);
    
    if ([2, 3, 4, 5, 8].includes(tipoUsuarioId)) {
        cargarUsuarios('all');
    }

    if ([1,6,7].includes(tipoUsuarioId)) {
        cargarUsuarios(carreraId);
    }
    
    if (carreraSelectSuperior) {
        carreraSelectSuperior.addEventListener('change', e => {
            const selectedValue = e.target.value;
            
            if (carreraSelectInferior) {
                carreraSelectInferior.value = selectedValue;
            }
            
            if ([2, 3, 4, 5, 8].includes(tipoUsuarioId)) {
                cargarUsuarios(selectedValue);
                inputNumeroEmpleado.value = ''; // Limpiar el campo de número de empleado al cambiar de carrera
            }
        });
    }
    
        if (carreraSelectInferior) {
        carreraSelectInferior.addEventListener('change', e => {
            const selectedValue = e.target.value;

            if (carreraSelectSuperior) {
                carreraSelectSuperior.value = selectedValue;
            }

            if ([2, 3, 4, 5, 8].includes(tipoUsuarioId)) {
                actualizarDocentesPorCarrera(selectedValue);
                cargarUsuarios(selectedValue); // <- Aquí actualizas el carrusel también
                inputNumeroEmpleado.value = ''; // Limpiar el campo de número de empleado al cambiar de carrera

            }
        });
    }

    // Evento que actualiza el número de empleado al seleccionar un docente
    if (usuarioSelect) {
        usuarioSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const numeroEmpleado = selectedOption.getAttribute('data-numero_empleado');

            console.log("Docente seleccionado:", selectedOption.textContent);
            console.log("Número de empleado encontrado:", numeroEmpleado);

            if (inputNumeroEmpleado) {
                inputNumeroEmpleado.value = numeroEmpleado || '';
            }
        });
    }
});

function actualizarDocentesPorCarrera(carreraId) {
    fetch(`../../models/obtener_usuarios.php?carrera_id=${carreraId}`)
    .then(res => res.json())
    .then(data => {
        if (!usuarioSelect) return;
        
        usuarioSelect.innerHTML = '<option value="">Selecciona un docente</option>';
        
        data.forEach(docente => {
            const option = document.createElement('option');
            option.value = docente.usuario_id;
            option.textContent = `${docente.nombre_usuario} ${docente.apellido_p} ${docente.apellido_m}`;
            option.setAttribute('data-numero_empleado', docente.numero_empleado); // ← agrega esto
            usuarioSelect.appendChild(option);

        });
    })
    .catch(error => console.error('Error al cargar docentes:', error));
}

// =================== CARGA DEL CARRUSEL DE TARJETAS ==========================
let swiper = null;

function cargarUsuarios(carreraId) {
    fetch(`../../models/obtener_usuarios.php?carrera_id=${carreraId}`)
        .then(res => res.json())
        .then(data => {
            const cardContainer = document.getElementById('card-container');
            if (!cardContainer) return;

            cardContainer.innerHTML = '';

            if (data.length === 0) {
                cardContainer.innerHTML = '<p style="color:black">No hay usuarios en esta división.</p>';
                return;
            }

            data.forEach(usuario => {
                const card = document.createElement('div');
                card.className = 'card-c swiper-slide';

                card.innerHTML = `
                    <div class="image-content">
                        <span class="overlay"></span>
                        <div class="card-image">
                            <img src="../${usuario.imagen_url || 'views/templates/assets/avatars/face-1.jpg'}" alt="perfil" class="card-img">
                        </div>
                    </div>
                    <div class="card-content">
                        <h2 class="name">${usuario.nombre_usuario} ${usuario.apellido_p}</h2>
                        <p class="description">${usuario.correo}</p>
                        <button class="button ver-perfil">Ver Perfil</button>
                        <div class="detalle-perfil" style="margin-top:10px; text-align:left; border-top:1px solid #ccc; padding-top:10px; font-size:14px;">
                            <p><strong>Edad:</strong> ${usuario.edad}</p>
                            <p><strong>Fecha de Contratación:</strong> ${usuario.fecha_contratacion}</p>
                            <p><strong>Carrera:</strong> ${usuario.nombre_carrera}</p>
                            <p><strong>Número de Empleado:</strong> ${usuario.numero_empleado}</p>
                            <p><strong>Grado Académico:</strong> ${usuario.grado_academico}</p>
                            <p><strong>Cédula:</strong> ${usuario.cedula}</p>
                        </div>
                    </div>
                `;
                cardContainer.appendChild(card);
            });

             // Actualizar el select de docentes solo si el rol es uno de los permitidos
            const selectDocente = document.getElementById('usuario_usuario_id');
            if (selectDocente) {
                selectDocente.innerHTML = '<option value="">Selecciona un docente</option>';

                if ([2, 3, 4, 5, 8].includes(tipoUsuarioId)) {
                    data.forEach(usuario => {
                        const option = document.createElement('option');
                        option.value = usuario.usuario_id;
                        option.textContent = `${usuario.nombre_usuario} ${usuario.apellido_p} ${usuario.apellido_m}`;
                        selectDocente.appendChild(option);
                    });
                }
            }

            // Llenar el select de docentes con data-numero_empleado
            if (usuarioSelect) {
                usuarioSelect.innerHTML = '<option value="">Selecciona un docente</option>';
                data.forEach(usuario => {
                    const option = document.createElement('option');
                    option.value = usuario.usuario_id;
                    option.textContent = `${usuario.nombre_usuario} ${usuario.apellido_p} ${usuario.apellido_m}`;
                    option.setAttribute('data-numero_empleado', usuario.numero_empleado);
                    usuarioSelect.appendChild(option);
                });

                // Seleccionar el usuario actual si es tipo 1, 6 o 7
                if ([1, 6, 7].includes(tipoUsuarioId)) {
                    usuarioSelect.value = idusuario.toString();
                }
            }


            // Activar eventos de "Ver Perfil"
            document.querySelectorAll('.ver-perfil').forEach(boton => {
                boton.addEventListener('click', () => {
                    const panel = boton.nextElementSibling;

                    document.querySelectorAll('.detalle-perfil').forEach(p => {
                        if (p !== panel) p.classList.remove('activo');
                    });

                    panel.classList.toggle('activo');
                });
            });

            // Reinicializa el Swiper
            if (swiper) {
                swiper.destroy(true, true);
            }

            swiper = new Swiper(".slide-content", {
                slidesPerView: 4,
                spaceBetween: 30,
                loop: data.length > 4,
                centerSlide: true,
                fade: true,
                grabCursor: true,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                    dynamicBullets: true,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                breakpoints: {
                    0: { slidesPerView: 1 },
                    520: { slidesPerView: 2 },
                    950: { slidesPerView: 3 },
                    1200: { slidesPerView: 4 },
                },
            });
        })
        .catch(err => console.error('Error al cargar usuarios:', err));
}

