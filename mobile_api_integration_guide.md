# Posyandu Mobile API Integration Guide

## Koneksi API Web & Mobile

API telah diperbarui untuk mendukung kedua platform (web admin dan mobile parent) dengan kontrol akses berdasarkan peran (role). Berikut adalah panduan untuk mengintegrasikan aplikasi mobile dengan API baru.

## Perubahan pada ApiService

Update file `api_service.dart` Anda dengan base URL yang benar:

```dart
class ApiService {
  static const String baseUrl = '192.168.1.4:8000/api';
  // Tambahkan path khusus untuk endpoint mobile
  static const String mobilePrefix = 'mobile';
  
  // Metode untuk endpoint mobile (gunakan untuk endpoint parent)
  String getMobileEndpoint(String endpoint) {
    return '$mobilePrefix/$endpoint';
  }

  // Gunakan method ini untuk endpoint parent
  Future<Map<String, dynamic>> getMobile(String endpoint) async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');

    final response = await http.get(
      Uri.parse('$baseUrl/${getMobileEndpoint(endpoint)}'),
      headers: {
        'Authorization': 'Bearer $token',
        'Content-Type': 'application/json',
      },
    );

    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('Failed to load data');
    }
  }

  // Metode get yang sudah ada tetap digunakan untuk general endpoint
  Future<Map<String, dynamic>> get(String endpoint) async {
    // Implementasi tetap sama
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('token');

    final response = await http.get(
      Uri.parse('$baseUrl/$endpoint'),
      headers: {
        'Authorization': 'Bearer $token',
        'Content-Type': 'application/json',
      },
    );

    if (response.statusCode == 200) {
      return json.decode(response.body);
    } else {
      throw Exception('Failed to load data');
    }
  }

  // Metode lainnya tetap sama
  // ...
}
```

## Perubahan pada AuthService

Update file `auth_service.dart` untuk menambahkan parameter role pada respons login:

```dart
Future<Map<String, dynamic>> login({
  required String nik,
  required String password,
}) async {
  try {
    print('Memulai proses login...');
    
    // Login dengan parameter platform=mobile
    final response = await http.post(
      Uri.parse('${ApiService.baseUrl}/login'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({
        'nik': nik,
        'password': password,
        'platform': 'mobile', // Tambahkan parameter ini
      }),
    );
    
    final data = jsonDecode(response.body);
    
    if (response.statusCode == 200 && data['status'] == 'success') {
      print('Login berhasil');
      final prefs = await SharedPreferences.getInstance();
      
      // Store user data from API
      await prefs.setString('token', data['token']);
      await prefs.setString('nik', data['pengguna']['nik']);
      await prefs.setString('nama_ibu', data['pengguna']['nama_ibu'] ?? '');
      await prefs.setString('role', data['pengguna']['role']); // Simpan role
      
      if (data['pengguna']['alamat'] != null) {
        await prefs.setString('alamat', data['pengguna']['alamat']);
      }
      
      if (data['pengguna']['usia'] != null) {
        await prefs.setInt('usia', data['pengguna']['usia']);
      }
      
      // Save current child data if available
      if (data['pengguna']['anak'] != null && data['pengguna']['anak'].isNotEmpty) {
        final currentChild = data['pengguna']['anak'][0];
        await prefs.setString('nama_anak', currentChild['nama']);
        await prefs.setInt('usia_bulan_anak', currentChild['usia_bulan']);
        await prefs.setString('jenis_kelamin_anak', currentChild['jenis_kelamin']);
      }

      return {
        'success': true,
        'message': data['message'] ?? 'Login berhasil',
        'data': data['pengguna'],
      };
    } else {
      return {
        'success': false,
        'message': data['message'] ?? 'NIK atau password salah',
      };
    }
  } catch (e) {
    print('Error during login: $e');
    return {
      'success': false,
      'message': 'Terjadi kesalahan: $e',
    };
  }
}
```

## Mengakses Endpoint Spesifik untuk Parent

Untuk mengakses endpoint parent, gunakan `getMobile` method dari ApiService:

```dart
// Contoh penggunaan
Future<List<Map<String, dynamic>>> getPerkembanganAnak(int anakId) async {
  try {
    // Gunakan getMobile untuk endpoint khusus parent
    final data = await _apiService.getMobile('perkembangan/anak/$anakId');
    
    if (data['status'] == 'success') {
      return List<Map<String, dynamic>>.from(data['perkembangan']);
    } else {
      throw Exception('Gagal mendapatkan data perkembangan anak');
    }
  } catch (e) {
    print('Error getting perkembangan anak: $e');
    return [];
  }
}
```

## Endpoint yang Tersedia

### Endpoint Umum (Menggunakan `get` biasa)
- `GET /api/anak/pengguna/{pengguna_id}` - Mendapatkan data anak untuk pengguna tertentu
- `GET /api/anak/{id}` - Mendapatkan data anak berdasarkan ID
- `GET /api/perkembangan/anak/{anak_id}` - Mendapatkan data perkembangan untuk anak tertentu
- `GET /api/perkembangan/{id}` - Mendapatkan data perkembangan berdasarkan ID
- `GET /api/stunting/anak/{anak_id}` - Mendapatkan data stunting untuk anak tertentu
- `GET /api/stunting/{id}` - Mendapatkan data stunting berdasarkan ID

### Endpoint Parent/Mobile (Menggunakan `getMobile`)
- `GET /api/mobile/anak/pengguna/{pengguna_id}` - Mendapatkan data anak untuk pengguna tertentu (parent only)
- `GET /api/mobile/anak/{id}` - Mendapatkan data anak berdasarkan ID (parent only)
- `GET /api/mobile/perkembangan/anak/{anak_id}` - Mendapatkan data perkembangan untuk anak tertentu (parent only)
- `GET /api/mobile/perkembangan/{id}` - Mendapatkan data perkembangan berdasarkan ID (parent only)
- `GET /api/mobile/stunting/anak/{anak_id}` - Mendapatkan data stunting untuk anak tertentu (parent only)
- `GET /api/mobile/stunting/{id}` - Mendapatkan data stunting berdasarkan ID (parent only)

### Catatan Penting
1. API sekarang menyediakan keamanan berbasis peran (role-based security)
2. Role "parent" hanya bisa mengakses data baca
3. Role "admin" memiliki akses penuh (CRUD)
4. Login perlu menambahkan parameter `platform: 'mobile'` untuk aplikasi mobile
5. API akan menolak akses untuk role yang salah dengan kode status 403 