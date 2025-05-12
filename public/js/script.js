document.addEventListener('DOMContentLoaded', function() {
    const createLobbyBtn = document.getElementById('createLobbyBtn');
    const createLobbyPopup = document.getElementById('createLobbyPopup');
    const closePopup = document.querySelector('.close-popup');
    
    if (createLobbyBtn && createLobbyPopup) {
        createLobbyBtn.addEventListener('click', function() {
            createLobbyPopup.style.display = 'block';
            document.body.style.overflow = 'hidden';
        });
    }

    if (closePopup) {
        closePopup.addEventListener('click', function() {
            createLobbyPopup.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
    }

    if (createLobbyPopup) {
        window.addEventListener('click', function(event) {
            if (event.target === createLobbyPopup) {
                createLobbyPopup.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });
    }

    const createLobbyForm = document.querySelector('.create-lobby-form');
    if (createLobbyForm) {
        createLobbyForm.addEventListener('submit', function(event) {
            event.preventDefault();
            clearErrors();

            const formData = new FormData(createLobbyForm);

            if (!validateInputs(
                formData.get('game_name').trim(),
                formData.get('skill_level').trim(),
                formData.get('playstyle').trim(),
                formData.get('region').trim(),
                formData.get('max_players').trim()
            )) {
                return;
            }

            fetch(createLobbyForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error(`Error: ${response.status} - ${text}`);
                        throw new Error(`Error: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                createLobbyPopup.style.display = 'none';
                document.body.style.overflow = 'auto';
                createLobbyForm.reset();
                window.location.href = '/fyt';
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
        });
    }

    const joinLobbyForms = document.querySelectorAll('.join-lobby-form');
    joinLobbyForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            clearErrors();

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error(`Error: ${response.status} - ${text}`);
                        throw new Error(`Error: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                window.location.reload();
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
        });
    });
 




    const deleteLobbyForms = document.querySelectorAll('.delete-lobby-form');
    deleteLobbyForms.forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            clearErrors();
    
            fetch(form.action, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error(`Error: ${response.status} - ${text}`);
                        throw new Error(`Error: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
        });
    });
    
    

    function validateInputs(gameName, skillLevel, playstyle, region, maxPlayers, photo) {
        clearErrors(); // Clear any previous errors before new validation
        let valid = true;
    
        if (gameName === '') {
            showError('game_name', 'Game name is required.');
            valid = false;
        }
        if (skillLevel === '') {
            showError('skill_level', 'Skill level is required.');
            valid = false;
        }
        if (playstyle === '') {
            showError('playstyle', 'Playstyle is required.');
            valid = false;
        }
        if (region === '') {
            showError('region', 'Region is required.');
            valid = false;
        }
        if (maxPlayers === '' || isNaN(maxPlayers) || maxPlayers < 1 || maxPlayers > 10) {
            showError('max_players', 'Please enter a valid number between 1 and 10.');
            valid = false;
        }
    
        // Check if the photo is provided and validate its type
        if (photo && photo.type && !['image/png', 'image/jpeg'].includes(photo.type)) {
            showError('photo', 'Please upload a valid PNG or JPG image.');
            valid = false;
        }        
    
        return valid;
    }
    
    function showError(fieldId, message) {
        const field = document.getElementById(fieldId);
    
        // Check if an error message already exists for this field
        if (field.parentElement.querySelector('.error-message')) return;
    
        const errorSpan = document.createElement('span');
        errorSpan.classList.add('error-message');
        errorSpan.style.color = 'red';
        errorSpan.textContent = message;
        field.parentElement.appendChild(errorSpan);
        field.classList.add('error');
    }
    
    function clearErrors() {
        const errorMessages = document.querySelectorAll('.error-message');
        errorMessages.forEach(msg => msg.remove());
    
        const errorFields = document.querySelectorAll('.error');
        errorFields.forEach(field => field.classList.remove('error'));
    }
    
    // Sidebar functionality
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const closeSidebar = document.getElementById('closeSidebar');
    let isSidebarOpen = false;

    function openSidebar() {
        if (sidebar) {
            sidebar.style.right = '0';
            isSidebarOpen = true;
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }
    }

    function closeSidebarMenu() {
        if (sidebar) {
            sidebar.style.right = '-100%';
            isSidebarOpen = false;
            document.body.style.overflow = 'auto'; // Restore background scrolling
        }
    }

    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent event bubbling
            if (!isSidebarOpen) {
                openSidebar();
            }
        });
    }

    if (closeSidebar && sidebar) {
        closeSidebar.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent event bubbling
            closeSidebarMenu();
        });
    }

    // Close sidebar when clicking outside
    document.addEventListener('click', function(event) {
        if (isSidebarOpen && sidebar && !sidebar.contains(event.target) && event.target !== menuToggle) {
            closeSidebarMenu();
        }
    });

    // Close sidebar on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && isSidebarOpen) {
            closeSidebarMenu();
        }
    });
});
