document.addEventListener('DOMContentLoaded', function() {
    // Handle navigation links in the sidebar
    const navLinks = document.querySelectorAll('.sidebar-nav a');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Remove active class from all nav items
            navLinks.forEach(navLink => navLink.classList.remove('active'));
            // Add active class to clicked nav item
            this.classList.add('active');
            
            // If it's a link to the same page, prevent default behavior
            if (this.getAttribute('href') === window.location.pathname.split('/').pop()) {
                e.preventDefault();
            }
        });
    });

    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            
            // Remove the alert from the DOM after the fade out
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000); // 5 seconds
    });
});
