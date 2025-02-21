document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, script running');

    const menuIcon = document.querySelector('.menu-icon');
    const guestModal= document.getElementById('guest-menu-modal');
    const userModal = document.getElementById('user-menu-modal');
    const closeGuestModal = document.getElementById('close-guest-modal');
    const closeUserModal = document.getElementById('close-user-modal');

    console.log('Elements found:', {
        menuIcon: menuIcon ? 'Found' : 'Not found',
        guestModal: guestModal ? 'Found' : 'Not found',
        userModal: userModal ? 'Found' : 'Not found',
        isLoggedIn: window.appConfig ? window.appConfig.isLoggedIn : 'Config not found'
    });

    if(menuIcon) {
        menuIcon.addEventListener('click', function(e) {
            console.log('Menu icon clicked')
            e.preventDefault();

            if(window.appConfig.isLoggedIn) {
                console.log('User is logged in,showing user modal');
                userModal.style.display = 'flex';
            }else {
                console.log('User is not logged in, showing guest modal');
                guestModal.style.display= 'flex';
            }
        });
    } else {
        console.error('Menu icon not found in the document');
    }

    if(closeGuestModal) {
        closeGuestModal.addEventListener('click', function() {
            guestModal.style.display = 'none';
        });
    }

    if(closeUserModal) {
        closeUserModal.addEventListener('click', function() {
            userModal.style.display = 'none';
        })
    }

    window.addEventListener('click', function(e) {
        if(e.target === guestModal) {
            guestModal.style.display = 'none';
        }
        if(e.target === userModal) {
            userModal.style.display = 'none';
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            guestModal.style.display = 'none';
            userModal.style.display = 'none';
        }
    });
});
