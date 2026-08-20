// js/app.js
document.addEventListener("DOMContentLoaded", () => {
    fetch('data/content.json')
        .then(res => res.json())
        .then(data => {
            renderGeneral(data.general);
            renderServices(data.services, data.general.whatsapp);
            renderAreas(data.areas);
            renderFAQs(data.faqs);
        })
        .catch(err => {
            console.error("Error loading content settings:", err);
        });
});

function renderGeneral(general) {
    // Titles & brand names
    document.title = `${general.brandName} - Premium Home Service Massage in Bali`;
    document.getElementById('nav-brand').textContent = general.brandName;
    document.getElementById('footer-brand').textContent = general.brandName;
    document.getElementById('copyright-brand').textContent = general.brandName;
    
    document.getElementById('hero-title').textContent = general.tagline;
    document.getElementById('hero-desc').textContent = general.description;
    
    // Footer info
    const waLink = document.getElementById('footer-wa');
    waLink.href = `https://wa.me/${general.whatsapp}`;
    waLink.textContent = `+${general.whatsapp}`;
    
    document.getElementById('footer-hours').textContent = general.operatingHours;
    
    const instaLink = document.getElementById('footer-insta');
    if (general.instagram) {
        instaLink.href = general.instagram;
        instaLink.classList.remove('hidden');
    } else {
        instaLink.classList.add('hidden');
    }

    // Set Copyright Year
    document.getElementById('copyright-year').textContent = new Date().getFullYear();
}

function renderServices(services, whatsapp) {
    const listContainer = document.getElementById('services-list');
    listContainer.innerHTML = ''; // Clear loading skeleton placeholders

    services.forEach(service => {
        // Generate select dropdown element for duration options
        let optionsHTML = '';
        service.options.forEach((opt, index) => {
            optionsHTML += `<option value="${opt.duration}" data-price="${opt.price}">${opt.duration} - ${opt.price}</option>`;
        });

        const card = document.createElement('div');
        card.className = "bg-white rounded-2xl overflow-hidden border border-theme-100 shadow-sm hover:shadow-md transition-shadow flex flex-col";
        card.innerHTML = `
            <div class="h-56 bg-stone-100 overflow-hidden relative">
                <img src="${service.image}" alt="${service.title}" class="w-full h-full object-cover">
            </div>
            <div class="p-6 flex-1 flex flex-col justify-between">
                <div class="space-y-3">
                    <h3 class="text-xl font-bold text-theme-900">${service.title}</h3>
                    <p class="text-stone-600 text-sm leading-relaxed">${service.description}</p>
                </div>
                
                <div class="mt-6 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-stone-400 mb-1">Select Duration</label>
                        <select id="select-${service.id}" class="w-full border border-stone-200 bg-stone-50 px-3 py-2 rounded-xl text-sm font-medium focus:ring-2 focus:ring-theme-500 focus:outline-none">
                            ${optionsHTML}
                        </select>
                    </div>
                    
                    <button onclick="bookService('${service.title}', '${service.id}', '${whatsapp}')" class="w-full bg-theme-600 hover:bg-theme-700 text-white font-semibold py-3 px-4 rounded-xl text-sm tracking-wide text-center transition-all flex items-center justify-center space-x-2">
                        <span>Book via WhatsApp</span>
                    </button>
                </div>
            </div>
        `;
        listContainer.appendChild(card);
    });
}

function renderAreas(areas) {
    const container = document.getElementById('areas-list');
    container.innerHTML = '';
    
    areas.forEach(area => {
        const li = document.createElement('li');
        li.className = "flex items-center space-x-3 text-stone-600 text-sm";
        li.innerHTML = `
            <span class="text-theme-600 text-lg">✓</span>
            <span class="font-medium">${area}</span>
        `;
        container.appendChild(li);
    });
}

function renderFAQs(faqs) {
    const container = document.getElementById('faqs-list');
    container.innerHTML = '';

    faqs.forEach((faq, index) => {
        const faqEl = document.createElement('div');
        faqEl.className = "bg-white border border-theme-100 rounded-2xl overflow-hidden";
        faqEl.innerHTML = `
            <button onclick="toggleFaq(${index})" class="w-full flex items-center justify-between p-6 text-left font-semibold text-theme-900 hover:bg-theme-50/50 transition-colors">
                <span>${faq.question}</span>
                <span id="faq-icon-${index}" class="text-theme-600 transition-transform duration-200">+</span>
            </button>
            <div id="faq-ans-${index}" class="hidden px-6 pb-6 text-sm text-stone-600 leading-relaxed border-t border-stone-50 pt-4">
                ${faq.answer}
            </div>
        `;
        container.appendChild(faqEl);
    });
}

function toggleFaq(index) {
    const ans = document.getElementById(`faq-ans-${index}`);
    const icon = document.getElementById(`faq-icon-${index}`);
    const isHidden = ans.classList.contains('hidden');
    
    // Hide all first
    document.querySelectorAll("[id^='faq-ans-']").forEach(el => el.classList.add('hidden'));
    document.querySelectorAll("[id^='faq-icon-']").forEach(el => el.textContent = '+');

    if (isHidden) {
        ans.classList.remove('hidden');
        icon.textContent = '−';
    }
}

function bookService(serviceName, selectId, whatsapp) {
    const select = document.getElementById(`select-${selectId}`);
    const duration = select.value;
    const selectedOption = select.options[select.selectedIndex];
    const price = selectedOption.getAttribute('data-price');
    
    const message = `Hi, I would like to book a ${serviceName} (${duration} - ${price}). Here are my details:
- Date & Time: 
- Address (Hotel/Villa/Home): 
- Number of People: 

Please confirm my booking. Thank you!`;
    
    const waUrl = `https://wa.me/${whatsapp}?text=${encodeURIComponent(message)}`;
    window.open(waUrl, '_blank');
}
