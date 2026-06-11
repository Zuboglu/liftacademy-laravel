<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CertificateConfig;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCertificateController extends Controller
{
    public function index(Request $request)
    {
        $query = Certificate::with(['user', 'course'])
            ->orderByDesc('created_at');

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('cert_number', 'like', '%'.$request->q.'%')
                  ->orWhere('recipient_name', 'like', '%'.$request->q.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $certificates = $query->paginate(20)->withQueryString();
        return view('admin.certificates.index', compact('certificates'));
    }

    public function create()
    {
        $users   = User::orderBy('name')->get();
        $courses = Course::where('published', true)->orderBy('title')->get();
        return view('admin.certificates.create', compact('users', 'courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'          => 'required|exists:users,id',
            'course_id'        => 'nullable|exists:courses,id',
            'level'            => 'required|in:JUNIOR,OPERATOR,SENIOR,SUPERVISOR,TRAINER',
            'status'           => 'required|in:ACTIVE,EXPIRED,REVOKED',
            'recipient_name'   => 'required|string|max:255',
            'instructor_name'  => 'nullable|string|max:255',
            'training_hours'   => 'nullable|integer|min:1',
            'department'       => 'nullable|string|max:255',
            'site'             => 'nullable|string|max:255',
            'employee_id'      => 'nullable|string|max:100',
            'notes'            => 'nullable|string',
            'completed_at'     => 'nullable|date',
            'expires_at'       => 'nullable|date',
        ]);

        $data['cert_number'] = 'LIFT-' . strtoupper(Str::random(4)) . '-' . date('Y') . '-' . str_pad($data['user_id'], 4, '0', STR_PAD_LEFT);
        $data['issued_at']   = now();

        $cert = Certificate::create($data);

        return redirect()->route('admin.certificates.show', $cert->id)
            ->with('success', 'Sertifika oluşturuldu.');
    }

    public function show(Certificate $certificate)
    {
        $certificate->load(['user', 'course']);

        $certConfig = $certificate->course_id
            ? CertificateConfig::with('prerequisites')->where('course_id', $certificate->course_id)->first()
            : null;
        $prereqIds  = $certConfig?->prerequisites->pluck('id')->toArray() ?? [];
        $allCourses = Course::when($certificate->course_id, fn($q) => $q->where('id', '!=', $certificate->course_id))
            ->orderBy('title')->get(['id', 'title']);

        return view('admin.certificates.show', compact('certificate', 'certConfig', 'prereqIds', 'allCourses'));
    }

    public function updatePrerequisites(Request $request, Certificate $certificate)
    {
        if (!$certificate->course_id) {
            return response()->json(['success' => false, 'message' => 'Bu sertifikaya kurs atanmamış. Önce kursu düzenleyin.'], 422);
        }

        $prereqs = array_filter((array) $request->input('prerequisites', []), 'is_numeric');

        $config = CertificateConfig::firstOrCreate(
            ['course_id' => $certificate->course_id],
            ['cert_level' => $certificate->level, 'completion_days' => 30,
             'validity_days' => 365, 'requires_quiz' => true, 'min_watch_pct' => 80]
        );

        $config->prerequisites()->sync($prereqs);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Ön koşullar kaydedildi.']);
        }
        return back()->with('success', 'Sertifika ön koşulları güncellendi.');
    }

    public function edit(Certificate $certificate)
    {
        $users   = User::orderBy('name')->get();
        $courses = Course::where('published', true)->orderBy('title')->get();
        return view('admin.certificates.edit', compact('certificate', 'users', 'courses'));
    }

    public function update(Request $request, Certificate $certificate)
    {
        $data = $request->validate([
            'level'           => 'required|in:JUNIOR,OPERATOR,SENIOR,SUPERVISOR,TRAINER',
            'status'          => 'required|in:ACTIVE,EXPIRED,REVOKED',
            'recipient_name'  => 'required|string|max:255',
            'instructor_name' => 'nullable|string|max:255',
            'training_hours'  => 'nullable|integer|min:1',
            'department'      => 'nullable|string|max:255',
            'site'            => 'nullable|string|max:255',
            'employee_id'     => 'nullable|string|max:100',
            'notes'           => 'nullable|string',
            'completed_at'    => 'nullable|date',
            'expires_at'      => 'nullable|date',
        ]);

        $certificate->update($data);

        return redirect()->route('admin.certificates.show', $certificate->id)
            ->with('success', 'Sertifika güncellendi.');
    }

    public function destroy(Certificate $certificate)
    {
        $certificate->delete();
        return redirect()->route('admin.certificates.index')
            ->with('success', 'Sertifika silindi.');
    }
}
