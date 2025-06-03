// Bootstrap modul betöltése (pl. Axios, Laravel Echo stb.)
import './bootstrap';

// === Mobilmenü megnyitása/bezárása ===
window.toggleMobileMenu = function () {
    const menu = document.querySelector('.mobile-menu');
    if (menu) {
        menu.classList.toggle('show'); // 'show' osztály hozzáadása/eltávolítása
    }
};

// === Asztali keresősáv megjelenítése/eltüntetése ===
window.toggleSearchBar = function () {
    const searchForm = document.getElementById('search-bar');
    searchForm?.classList.toggle('show'); // ? biztonsági ellenőrzés
};

// === Mobil keresősáv megjelenítése/eltüntetése ===
window.toggleMobileSearchBar = function () {
    const searchForm = document.getElementById('mobile-search-bar');
    searchForm?.classList.toggle('show');
};

// === Ajánlott termékek vízszintes görgetése (balra vagy jobbra) ===
window.scrollRecommendations = function (direction) {
    const container = document.getElementById('recommendation-track');
    const card = container?.querySelector('.product-card');
    if (!container || !card) return;

    // A görgetési távolság: termékkártya szélessége + 20px (margin)
    const scrollAmount = card.offsetWidth + 20;

    // Görgetés a kívánt irányba (smooth animációval)
    container.scrollBy({ left: direction * scrollAmount, behavior: 'smooth' });
};

// === Rendezés beküldése és pozíció megjegyzése görgetéshez ===
window.handleSortAndScroll = function () {
    const form = document.getElementById('sort-form');
    if (!form) return;

    // Tároljuk a cél elem ID-jét localStorage-ben
    localStorage.setItem('scrollToAfterReload', 'product-list');

    // Beküldjük az űrlapot
    form.submit();
};

// === Rendezés visszaállítása alapértelmezettre ===
window.resetSorting = function () {
    const form = document.getElementById('sort-form');
    const sortSelect = document.getElementById('sort');

    if (!form || !sortSelect) return;

    // Select érték törlése és újraküldés
    sortSelect.value = "";
    localStorage.setItem('scrollToAfterReload', 'product-list');
    form.submit();
};

// === Keresés esetén automatikus görgetés a terméklistára ===
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const target = document.getElementById('product-list');
    if (urlParams.has('search') && target) {
        target.scrollIntoView({ behavior: 'smooth' }); // Simán odagörget
    }
});

// === Újratöltés után visszagörgetés a korábbi szekcióhoz ===
document.addEventListener('DOMContentLoaded', () => {
    const scrollKey = 'scrollToAfterReload';
    const scrollTargetId = localStorage.getItem(scrollKey); // Pl. 'product-list'
    if (scrollTargetId) {
        const target = document.getElementById(scrollTargetId);
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' }); // Simán odagörget
        }
        localStorage.removeItem(scrollKey); // Egyszer használatos, töröljük
    }
});
