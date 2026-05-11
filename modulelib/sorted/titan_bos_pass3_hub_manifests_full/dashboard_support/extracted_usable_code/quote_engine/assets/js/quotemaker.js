document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('quotemaker-form');
    if (!form) return;
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = form.querySelector('button[type="submit"]') || form.querySelector('button');
        if (btn) { btn.disabled = true; btn.textContent = 'Preparing...'; }
        try {
            const res = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: {'X-Requested-With': 'XMLHttpRequest'} });
            const data = await res.json();
            console.log(data);
            alert('Quote visual payload prepared.');
        } catch (e) {
            console.error(e);
            alert('Could not prepare quote visual.');
        } finally {
            if (btn) { btn.disabled = false; btn.textContent = 'Prepare Quote Visual'; }
        }
    });
});
