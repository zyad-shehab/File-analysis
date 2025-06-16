@include('includes.bootstrap')    

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>رفع المستندات</title>
    @include('includes.bootstrap')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .upload-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 40px;
            margin-top: 30px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            backdrop-filter: blur(10px);
        }
        .upload-header {
            text-align: center;
            margin-bottom: 40px;
            color: #2c3e50;
        }
        .upload-header i {
            font-size: 60px;
            color: #e74c3c;
            margin-bottom: 20px;
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-20px);}
            60% {transform: translateY(-10px);}
        }
        .upload-form {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            border: 2px solid #e9ecef;
        }
        .file-upload-area {
            border: 3px dashed #dee2e6;
            border-radius: 15px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            margin-bottom: 25px;
        }
        .file-upload-area:hover {
            border-color: #e74c3c;
            background: #fff5f5;
        }
        .file-upload-area i {
            font-size: 40px;
            color: #e74c3c;
            margin-bottom: 15px;
        }
        .file-upload-area p {
            color: #6c757d;
            margin: 10px 0;
            font-size: 1.1em;
        }
        .btn-upload {
            background: #e74c3c;
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-size: 1.1em;
            transition: all 0.3s ease;
            width: 100%;
            max-width: 300px;
        }
        .btn-upload:hover {
            background: #c0392b;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
        }
        .btn-back {
            background: #34495e;
            border: none;
            border-radius: 10px;
            padding: 10px 25px;
            transition: all 0.3s ease;
        }
        .btn-back:hover {
            background: #2c3e50;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(52, 73, 94, 0.3);
        }
        .alert {
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            font-size: 1.1em;
        }
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        .custom-file-input {
            display: none;
        }
        .file-name {
            margin-top: 10px;
            color: #2c3e50;
            font-weight: 500;
        } .file-types {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }
        .file-type {
            text-align: center;
            padding: 15px;
            border-radius: 10px;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        .file-type:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .file-type i {
            font-size: 30px;
            margin-bottom: 10px;
        }
        .file-type.pdf i {
            color: #e74c3c;
        }
        .file-type.word i {
            color: #3498db;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="upload-container">
            <div class="upload-header">
                {{-- <i class="fas fa-file-pdf"></i> --}}
                <i class="fas fa-file-upload"></i>
                <h2>رفع المستندات</h2>
                <p class="text-muted">اختر ملف PDF أو Word لرفعه إلى النظام</p>
            </div>

            <div class="file-types">
                <div class="file-type pdf">
                    <i class="fas fa-file-pdf"></i>
                    <p>PDF</p>
                </div>
                <div class="file-type word">
                    <i class="fas fa-file-word"></i>
                    <p>Word</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            {{-- @else --}}
             @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    {{-- فشل في رفع الملف --}}
                     @foreach($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('documents.upload') }}" method="POST" enctype="multipart/form-data" class="upload-form">
                @csrf
                @method('post')
                
                <label class="file-upload-area" for="file-input">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>اسحب وأفلت الملف هنا</p>
                    <p>أو</p>
                    <button type="button" class="btn btn-outline-primary">اختر ملف</button>
                    {{-- <input type="file" name="file" id="file-input" class="custom-file-input" accept=".pdf" required> --}}
                    <input type="file" name="file[]" id="file-input" class="custom-file-input" accept=".pdf,.docx" required multiple>
                    

                    <div class="file-name"></div>
                </label>

                <div class="text-center">
                    <button type="submit" class="btn btn-upload text-white">
                        <i class="fas fa-upload"></i> رفع الملف
                    </button>
                </div>
            </form>

            <div class="text-center">
                <a href="{{ route('documents.list') }}" class="btn btn-back text-white">
                    <i class="fas fa-arrow-right"></i> العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <script>
        // تحديث اسم الملف عند اختياره
        document.getElementById('file-input').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            document.querySelector('.file-name').textContent = fileName || '';
        });

        // تفعيل زر اختيار الملف
        document.querySelector('.file-upload-area button').addEventListener('click', function() {
            document.getElementById('file-input').click();
        });
    </script>
</body>
</html>
