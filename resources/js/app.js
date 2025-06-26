// Importation des dépendances
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import '@fortawesome/fontawesome-free/js/all';
import 'bootstrap/dist/css/bootstrap.min.css';

// Attendre que le DOM soit chargé
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const sidebarToggle = document.getElementById('sidebarToggle');
    const mobileSidebar = document.getElementById('mobileSidebar');
    const body = document.body;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // Gestion du toggle sidebar
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            if (window.innerWidth < 992) { // Breakpoint cohérent avec le CSS
                // Version mobile - utiliser Offcanvas
                const bsOffcanvas = new bootstrap.Offcanvas(mobileSidebar);
                bsOffcanvas.show();
            } else {
                // Version desktop - toggle class
                body.classList.toggle('sidebar-collapsed');
                
                // Sauvegarder l'état
                saveSidebarState();
            }
        });
    }

    // Fonction pour sauvegarder l'état du sidebar
    function saveSidebarState() {
        if (!csrfToken) return;
        
        fetch('/toggle-sidebar', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                collapsed: body.classList.contains('sidebar-collapsed')
            })
        }).catch(error => {
            console.error('Error saving sidebar state:', error);
        });
    }

    // Bouton mobile flottant (seulement sur mobile)
    function createMobileMenuButton() {
        if (window.innerWidth >= 992) return;
        
        const mobileMenuToggle = document.createElement('button');
        mobileMenuToggle.id = 'mobileMenuButton';
        mobileMenuToggle.className = 'btn btn-primary rounded-circle d-lg-none position-fixed';
        mobileMenuToggle.style.bottom = '20px';
        mobileMenuToggle.style.right = '20px';
        mobileMenuToggle.style.width = '50px';
        mobileMenuToggle.style.height = '50px';
        mobileMenuToggle.style.zIndex = '1030';
        mobileMenuToggle.setAttribute('aria-label', 'Ouvrir le menu');
        mobileMenuToggle.innerHTML = '<i class="fas fa-bars"></i>';
        
        mobileMenuToggle.addEventListener('click', function() {
            const bsOffcanvas = new bootstrap.Offcanvas(mobileSidebar);
            bsOffcanvas.show();
        });

        // Ajouter seulement s'il n'existe pas déjà
        if (!document.getElementById('mobileMenuButton')) {
            document.body.appendChild(mobileMenuButton);
        }
    }

    // Gestion du redimensionnement
    function handleResize() {
        if (window.innerWidth >= 992) {
            // Sur desktop, s'assurer que le sidebar est visible
            body.classList.remove('sidebar-open');
            
            // Supprimer le bouton mobile si existe
            const mobileButton = document.getElementById('mobileMenuButton');
            if (mobileButton) {
                mobileButton.remove();
            }
        } else {
            // Sur mobile, créer le bouton si besoin
            createMobileMenuButton();
        }
    }
    
    window.addEventListener('resize', handleResize);

    handleResize();

    const navLinks = document.querySelectorAll('.sidebar-nav .nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 992 && mobileSidebar) {
                const bsOffcanvas = bootstrap.Offcanvas.getInstance(mobileSidebar);
                if (bsOffcanvas) {
                    bsOffcanvas.hide();
                }
            }
        });
    });
});