const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

const closeSidebar = () => {
    document.querySelector('#sidebar')?.classList.remove('show');
    document.querySelector('[data-close-sidebar]')?.classList.remove('show');
};

document.addEventListener('click', async (event) => {
    const sidebarButton = event.target.closest('[data-toggle-sidebar]');
    if (sidebarButton) {
        document.querySelector('#sidebar')?.classList.toggle('show');
        document.querySelector('[data-close-sidebar]')?.classList.toggle('show');
    }

    if (event.target.closest('[data-close-sidebar]')) {
        closeSidebar();
    }

    if (event.target.closest('.sidebar-nav a')) {
        closeSidebar();
    }

    const passwordButton = event.target.closest('[data-toggle-password]');
    if (passwordButton) {
        const input = passwordButton.closest('.input-group')?.querySelector('[data-password]');
        if (input) {
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            passwordButton.textContent = isHidden ? 'Hide' : 'Show';
            passwordButton.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Lihat password');
        }
    }

    const statusButton = event.target.closest('[data-status-url]');
    if (statusButton) {
        statusButton.disabled = true;
        try {
            const response = await fetch(statusButton.dataset.statusUrl, {
                method: 'PATCH',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ status: statusButton.dataset.statusValue }),
            });
            const data = await response.json();
            document.getElementById(statusButton.dataset.statusTarget).textContent = data.status;
        } finally {
            statusButton.disabled = false;
        }
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') closeSidebar();
});

document.querySelectorAll('.auto-alert').forEach((alert) => setTimeout(() => alert.remove(), 4000));

const revealItems = document.querySelectorAll('[data-reveal]');
if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.08 });
    revealItems.forEach((item) => observer.observe(item));
} else {
    revealItems.forEach((item) => item.classList.add('is-visible'));
}

document.querySelectorAll('[data-live-search]').forEach((input) => {
    input.addEventListener('input', () => {
        const keyword = input.value.toLowerCase().trim();
        const card = input.closest('.content-card');
        const rows = card?.querySelectorAll('.table-searchable tbody tr') ?? [];
        let visibleRows = 0;

        rows.forEach((row) => {
            const isEmptyState = row.querySelector('[colspan]');
            if (isEmptyState) return;
            const visible = row.textContent.toLowerCase().includes(keyword);
            row.style.display = visible ? '' : 'none';
            if (visible) visibleRows += 1;
        });

        card?.querySelector('[data-empty-search]')?.remove();
        if (keyword && visibleRows === 0) {
            const tbody = card.querySelector('.table-searchable tbody');
            const columnCount = card.querySelectorAll('.table-searchable thead th').length || 1;
            const emptyRow = document.createElement('tr');
            emptyRow.dataset.emptySearch = 'true';
            emptyRow.innerHTML = `<td colspan="${columnCount}" class="text-center text-secondary py-4">Data tidak ditemukan.</td>`;
            tbody?.appendChild(emptyRow);
        }
    });
});

document.querySelectorAll('[data-file-preview]').forEach((input) => {
    const preview = document.getElementById('filePreview');
    const updatePreview = () => {
        const file = input.files[0];
        preview.textContent = file ? `${file.name} (${Math.round(file.size / 1024)} KB)` : 'Pilih file atau tarik file ke area ini.';
    };
    input.addEventListener('change', updatePreview);
});

document.querySelectorAll('[data-drop-zone]').forEach((zone) => {
    const input = zone.querySelector('input[type="file"]');
    ['dragenter', 'dragover'].forEach((eventName) => {
        zone.addEventListener(eventName, (event) => {
            event.preventDefault();
            zone.classList.add('dragging');
        });
    });
    ['dragleave', 'drop'].forEach((eventName) => {
        zone.addEventListener(eventName, (event) => {
            event.preventDefault();
            zone.classList.remove('dragging');
        });
    });
    zone.addEventListener('drop', (event) => {
        if (!input || !event.dataTransfer.files.length) return;
        input.files = event.dataTransfer.files;
        input.dispatchEvent(new Event('change', { bubbles: true }));
    });
});

document.querySelectorAll('[data-smart-form]').forEach((form) => {
    const requiredFields = [...form.querySelectorAll('[required]')];
    const progress = document.querySelector('[data-form-progress]');
    const updateProgress = () => {
        const filled = requiredFields.filter((field) => field.value.trim()).length;
        const percentage = requiredFields.length ? Math.round((filled / requiredFields.length) * 100) : 0;
        if (progress) progress.style.width = `${percentage}%`;
    };
    requiredFields.forEach((field) => field.addEventListener('input', updateProgress));
    updateProgress();
});

document.querySelectorAll('[data-ajax-saw]').forEach((form) => {
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const loading = form.querySelector('[data-loading]');
        loading?.classList.remove('d-none');
        const response = await fetch(form.action, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: new FormData(form),
        });
        const data = await response.json();
        loading.textContent = `Selesai: ${data.processed} alternatif diproses, kuota ${data.quota}.`;
    });
});

if (window.chartData && document.getElementById('statsChart')) {
    const chartColors = ['#2563eb', '#0f766e', '#b45309', '#7c3aed', '#0891b2', '#16a34a', '#dc2626', '#475569'];
    new Chart(document.getElementById('statsChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(window.chartData),
            datasets: [{ label: 'Statistik', data: Object.values(window.chartData), backgroundColor: chartColors }],
        },
        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } },
    });
}
