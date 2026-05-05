    </main>
</div>

<script>
// Confirm delete shortcut
document.querySelectorAll('[data-confirm]').forEach(el=>{
    el.addEventListener('click', e=>{
        if(!confirm(el.dataset.confirm)) e.preventDefault();
    });
});
</script>
</body>
</html>
