
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.querySelector('header');
            const menuToggle = document.querySelector('.menu-toggle');
            const headerNav = document.querySelector('.header-nav');
            const dropdowns = document.querySelectorAll('.dropdown');
            const mediaQuery = window.matchMedia('(max-width: 768px)');

            // Control de clase 'scrolled' en el header
            window.addEventListener('scroll', function() {
                header.classList.toggle('scrolled', window.scrollY > 10);
            });

            // Función para manejar el menú hamburguesa
            menuToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                this.classList.toggle('active');
                headerNav.classList.toggle('active');

                // Si el menú se está cerrando, cierra todos los dropdowns
                if (!headerNav.classList.contains('active')) {
                    dropdowns.forEach(dropdown => {
                        dropdown.classList.remove('active');
                    });
                } else {
                    // Si el menú se está abriendo, aplica animaciones de cascada
                    const navItems = headerNav.querySelectorAll('a, .dropdown, form');
                    navItems.forEach((item, index) => {
                        item.style.animationDelay = `${0.1 * index}s`;
                    });
                }
            });

            // Función para manejar los dropdowns
            dropdowns.forEach(dropdown => {
                const dropbtn = dropdown.querySelector('.dropbtn');

                dropbtn.addEventListener('click', function(e) {
                    if (mediaQuery.matches) {
                        e.preventDefault();
                        e.stopPropagation();

                        dropdowns.forEach(otherDropdown => {
                            if (otherDropdown !== dropdown && otherDropdown.classList.contains('active')) {
                                otherDropdown.classList.remove('active');
                            }
                        });
                        dropdown.classList.toggle('active');
                    }
                });

                // Manejar hover en desktop
                // Para el dropdown de operaciones, agregamos un pequeño retraso al ocultar
                // para que el usuario tenga tiempo de mover el cursor al contenido.
                dropdown.addEventListener('mouseenter', function() {
                    if (!mediaQuery.matches) {
                        clearTimeout(this.leaveTimeout); // Limpia el temporizador de ocultar si existe
                        this.classList.add('active');
                    }
                });

                dropdown.addEventListener('mouseleave', function() {
                    if (!mediaQuery.matches) {
                        this.leaveTimeout = setTimeout(() => {
                            this.classList.remove('active');
                        }, 200); // Pequeño retraso de 200ms para cerrar
                    }
                });
            });

            // Cerrar menús y dropdowns al hacer clic fuera
            document.addEventListener('click', function(e) {
                if (headerNav.classList.contains('active') && !e.target.closest('.header-nav') && !e.target.closest('.menu-toggle')) {
                    headerNav.classList.remove('active');
                    menuToggle.classList.remove('active');
                    dropdowns.forEach(dropdown => {
                        dropdown.classList.remove('active');
                    });
                }

                dropdowns.forEach(dropdown => {
                    if (dropdown.classList.contains('active') && !dropdown.contains(e.target)) {
                        dropdown.classList.remove('active');
                    }
                });
            });

            // Manejar cambios de tamaño de pantalla
            window.addEventListener('resize', function() {
                if (!mediaQuery.matches) {
                    headerNav.classList.remove('active');
                    menuToggle.classList.remove('active');

                    dropdowns.forEach(dropdown => {
                        dropdown.classList.remove('active');
                    });
                }
            });
        });
