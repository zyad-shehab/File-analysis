@include('includes.bootstrap')
<style>
    html, body {
        height: 100%;
    }
    body {
        background: #f7fafd;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .register-card {
        background: #fff;
        border-radius: 28px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        max-width: 400px;
        width: 100%;
        padding: 40px 32px 32px 32px;
        direction: rtl;
        margin: 0 auto;
    }
    .form-control, .form-select {
        border-radius: 12px;
        margin-bottom: 16px;
        background: #f7fafd;
        border: 1px solid #e0e0e0;
    }
    .form-label {
        font-weight: 500;
        margin-bottom: 4px;
    }
    .register-btn {
        width: 100%;
        border-radius: 10px;
        font-size: 1rem;
        padding: 6px 0;
    }
</style>


 

<html>
<body>
<div class="register-card">
    @if(session('danger'))
                <div class="alert alert-danger">
                {{ session('danger') }}
                </div>
    @endif
    <form action="{{route('authenticate') }}" method="POST">
        @csrf
        <h1 class="text-center mb-4" style="font-weight:700;">تسجيل الدخول</h1>
        <div class="mb-3">
            <input type="text" name="email" class="form-control" placeholder="اسم المستخدم">
        </div>
        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="كلمة المرور">
        </div>
        <button type="submit" class="btn btn-success btn-sm register-btn">دخول</button>
       
       
    </form>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> 
</body>
</html>