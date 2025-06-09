<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>قائمة المستندات</title>
    @include('includes.bootstrap')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .main-container {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.08);
            padding: 30px 20px;
            margin-top: 30px;
        }
        .table thead {
            background: #f8f9fa;
        }
        .stats-table td {
            padding: 10px 20px;
        }
        .btn-custom {
            border-radius: 8px;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .search-input, .sort-select {
            border-radius: 8px;
            padding: 8px 15px;
            border: 1px solid #ddd;
        }
        .search-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        .stats-container {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container main-container">
        <h2 class="mb-4 text-center">قائمة المستندات المحللة</h2>
        <div class="row mb-3">
            <div class="col-md-6 mb-2">
                <form method="GET" action="{{ route('documents.list') }}" class="d-flex align-items-center gap-2">
                    <label for="sort" class="mb-0 me-2">ترتيب حسب العنوان:</label>
                    <select name="sort" class="form-select sort-select w-auto" onchange="this.form.submit()">
                        <option value="asc" {{ $sortOrder == 'asc' ? 'selected' : '' }}>A to z</option>
                        <option value="desc" {{ $sortOrder == 'desc' ? 'selected' : '' }}>Z to a</option>
                    </select>
                </form>
            </div>
            <div class="col-md-6 mb-2">
                <form method="GET" action="{{ route('documents.search') }}" class="d-flex align-items-center gap-2 justify-content-md-end">
                    <input type="text" name="q" class="form-control search-input" placeholder="ابحث في محتوى المستند..." value="{{ request('q') }}" required>
                    <button type="submit" class="btn btn-primary btn-custom">
                        <i class="fas fa-search"></i> بحث
                    </button>
                </form>
            </div>
        </div>
        <div class="mb-3 text-end">
            <a href="{{ route('documents.form') }}" class="btn btn-success btn-custom">
                <i class="fas fa-upload"></i> رفع مستند جديد
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead>
                    <tr>
                        <th>العنوان</th>
                        <th>الحجم (كيلو بايت)</th>
                        <th>تاريخ الرفع</th>
                        <th>رابط التنزيل</th>
                        <th>التصنيف</th>
                        <th>حذف الملف</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                        <tr>
                            <td>{{ $doc->title }}</td>
                            <td>{{ number_format($doc->size /1024) }}</td>
                            <td>{{ $doc->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                {{-- <a href="{{ asset('storage/' . $doc->file_path) }}" class="btn btn-sm btn-primary" target="_blank"> --}}
                                <a href="{{ route('documents.download', $doc->id) }}" class="btn btn-sm btn-primary">

                                    <i class="fas fa-download"></i> تحميل
                                </a>
                            </td>
                            <td>{{ $doc->category ?? 'غير مصنف' }}</td>
                            <td>
                                {{-- <a href="{{ route('documents.destroy', $doc->id) }}">حذف</a> --}}
                                <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" class="delete-form" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">لا توجد مستندات مرفوعة بعد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $documents->links('pagination::bootstrap-5') }}
            </div>
        </div>
        <div class="stats-container mt-5">
            <h4 class="text-center mb-4">📊 إحصائيات المستندات</h4>
            <table class="table stats-table table-bordered text-center w-75 mx-auto">
                <tbody>
                    <tr>
                        <td>عدد المستندات</td>
                        <td>{{ $totalDocuments }}</td>
                    </tr>
                    <tr>
                        <td>إجمالي حجم الملفات</td>
                        <td>{{ $totalSizeMB }} MB</td>
                    </tr>
                    <tr>
                        <td>متوسط وقت البحث</td>
                        <td>{{ $averageSearchTime }} sec</td>
                    </tr>
                    <tr>
                        <td>متوسط وقت التصنيف</td>
                        <td>{{ $averageClassifyTime }} sec</td>
                    </tr>
                    <tr>
                        <td>متوسط وقت الترتيب</td>
                        <td>{{ $averageSortTime }} sec</td>
                    </tr>
                </tbody>
            </table>
            <!-- <div class="text-center mt-3">
                <a href="{{ route('documents.statistics') }}" class="btn btn-primary btn-custom">
                    <i class="fas fa-chart-bar"></i> عرض إحصائيات المستندات
                </a>
            </div> -->
        </div>
    </div>
</body>

<script>

$(document).ready(function() {
    $('.delete-form').on('submit', function(e) {
        e.preventDefault(); // منع الإرسال الافتراضي للنموذج
        var form = this; // الإشارة إلى النموذج الحالي

        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: "لن تتمكن من التراجع عن هذا!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذف!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // إذا أكد المستخدم، قم بإرسال النموذج يدويًا
            }
        });
    });
});
</script>

</html>
