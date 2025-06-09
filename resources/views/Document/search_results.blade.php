<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>نتائج البحث</title>
    @include('includes.bootstrap')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .main-container {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.08);
            padding: 30px;
            margin-top: 20px;
        }
        .search-header {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .result-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
            margin-bottom: 20px;
            border: 1px solid #eee;
        }
        .result-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .result-card .card-body {
            padding: 20px;
        }
        .result-card h4 {
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .result-content {
            color: #34495e;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        mark {
            background-color: #fff3cd;
            padding: 2px 5px;
            border-radius: 3px;
        }
        .btn-back {
            border-radius: 8px;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }
        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .btn-file {
            border-radius: 8px;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }
        .btn-file:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .no-results {
            background: #fff3cd;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container main-container">
        <div class="search-header">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">نتائج البحث</h2>
                <a href="{{ route('documents.list') }}" class="btn btn-primary btn-back">
                    <i class="fas fa-arrow-right"></i> العودة للقائمة
                </a>
            </div>
            <div class="alert alert-info">
                <i class="fas fa-search"></i> تم البحث عن: "<strong>{{ $keyword }}</strong>"
            </div>
        </div>

        @php
            use Illuminate\Support\Str;
            $words = array_filter(explode(' ', $keyword), fn($w) => trim($w) !== '');
        @endphp

        @forelse($documents as $doc)
            <div class="result-card">
                <div class="card-body">
                    <h4>
                        @php
                            $highlightedTitle = $doc->title;
                            foreach ($words as $kw) {
                                $highlightedTitle = preg_replace('/(' . preg_quote($kw, '/') . ')/iu', '<mark>$1</mark>', $highlightedTitle);
                            }
                        @endphp
                        {!! $highlightedTitle !!}
                    </h4>

                    <div class="result-content">
                        @php
                            $highlightedContent = $doc->content;
                            foreach ($words as $kw) {
                                $highlightedContent = preg_replace('/(' . preg_quote($kw, '/') . ')/iu', '<mark>$1</mark>', $highlightedContent);
                            }
                        @endphp
                        {!! $highlightedContent !!}
                    </div>

                    <a href="{{ route('documents.download', $doc->id) }}" class="btn btn-primary btn-file">
                    {{-- <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-primary btn-file"> --}}
                        <i class="fas fa-file-alt"></i> فتح الملف
                    </a>
                </div>
            </div>
        @empty
            <div class="no-results">
                <i class="fas fa-exclamation-circle fa-2x mb-3"></i>
                <h4>لا توجد نتائج مطابقة</h4>
                <p class="text-muted">جرب البحث بكلمات مختلفة أو العودة للقائمة الرئيسية</p>
            </div>
        @endforelse
    </div>
    <div class="d-flex justify-content-center">
            {{ $documents->links('pagination::bootstrap-5') }}
    </div>
</body>
</html>
