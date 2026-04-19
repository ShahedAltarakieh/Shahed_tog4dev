{{-- Unified Admin Toast (top-right pill) --}}
<style>
.admin-toast-stack{
    position:fixed;
    top:18px;
    {{ app()->getLocale()=='ar' ? 'left:18px' : 'right:18px' }};
    z-index:99999;
    display:flex;
    flex-direction:column;
    gap:10px;
    pointer-events:none;
    max-width:360px;
}
.admin-toast{
    pointer-events:auto;
    background:#fff;
    border-radius:10px;
    box-shadow:0 8px 28px rgba(0,0,0,.12),0 2px 6px rgba(0,0,0,.06);
    padding:12px 16px 12px 14px;
    display:flex;
    align-items:center;
    gap:12px;
    min-width:260px;
    border-{{ app()->getLocale()=='ar' ? 'right' : 'left' }}:4px solid #28a745;
    font-family:inherit;
    font-size:.9rem;
    color:#222;
    transform:translateY(-12px);
    opacity:0;
    transition:transform .25s ease,opacity .25s ease;
}
.admin-toast.show{transform:translateY(0);opacity:1}
.admin-toast.hide{transform:translateY(-12px);opacity:0}
.admin-toast .at-icon{
    width:26px;height:26px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    flex-shrink:0;font-size:.85rem;color:#fff;
}
.admin-toast .at-msg{flex:1;line-height:1.35;font-weight:500}
.admin-toast .at-close{
    background:transparent;border:none;color:#9aa0a6;
    font-size:1.05rem;cursor:pointer;padding:2px 6px;line-height:1;
    border-radius:6px;transition:background .15s ease;
}
.admin-toast .at-close:hover{background:rgba(0,0,0,.06);color:#444}
.admin-toast.success{border-color:#28a745}
.admin-toast.success .at-icon{background:#28a745}
.admin-toast.error{border-color:#dc3545}
.admin-toast.error .at-icon{background:#dc3545}
.admin-toast.warning{border-color:#e6a800}
.admin-toast.warning .at-icon{background:#e6a800}
.admin-toast.info{border-color:#13585D}
.admin-toast.info .at-icon{background:#13585D}
</style>
<div class="admin-toast-stack" id="adminToastStack" aria-live="polite" aria-atomic="true"></div>
<script>
(function(){
    var icons={success:'\u2713',error:'\u2715',warning:'!',info:'i'};
    function ensureStack(){
        var s=document.getElementById('adminToastStack');
        if(!s){s=document.createElement('div');s.id='adminToastStack';s.className='admin-toast-stack';document.body.appendChild(s);}
        return s;
    }
    window.adminToast=function(type,message,opts){
        opts=opts||{};
        type=(type||'success').toLowerCase();
        if(!icons[type])type='info';
        var stack=ensureStack();
        var el=document.createElement('div');
        el.className='admin-toast '+type;
        el.innerHTML='<span class="at-icon">'+icons[type]+'</span>'+
                     '<span class="at-msg"></span>'+
                     '<button type="button" class="at-close" aria-label="Close">&times;</button>';
        el.querySelector('.at-msg').textContent=message||'';
        stack.appendChild(el);
        requestAnimationFrame(function(){el.classList.add('show');});
        var timeout=opts.duration||3500;
        var timer=setTimeout(close,timeout);
        function close(){
            clearTimeout(timer);
            el.classList.remove('show');el.classList.add('hide');
            setTimeout(function(){el.parentNode&&el.parentNode.removeChild(el);},260);
        }
        el.querySelector('.at-close').addEventListener('click',close);
        return{close:close};
    };
    document.addEventListener('DOMContentLoaded',function(){
        @if(session('success'))
            window.adminToast('success',@json(session('success')));
        @endif
        @if(session('error'))
            window.adminToast('error',@json(session('error')));
        @endif
        @if(session('warning'))
            window.adminToast('warning',@json(session('warning')));
        @endif
        @if(session('info'))
            window.adminToast('info',@json(session('info')));
        @endif
        @if($errors->any())
            window.adminToast('error',@json($errors->first()));
        @endif
        document.querySelectorAll('.alert.alert-success,.alert.alert-danger,.alert.alert-warning,.alert.alert-info').forEach(function(a){
            if(a.dataset.adminToastIgnore!==undefined)return;
            var msg=(a.textContent||'').replace(/[\u00d7\xd7\s]+$/,'').trim();
            if(!msg)return;
            var t='info';
            if(a.classList.contains('alert-success'))t='success';
            else if(a.classList.contains('alert-danger'))t='error';
            else if(a.classList.contains('alert-warning'))t='warning';
            window.adminToast(t,msg);
            a.style.display='none';
        });
    });
})();
</script>
