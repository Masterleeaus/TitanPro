# ZeroPay Mobile App

A Flutter mobile application for ZeroPay — a QR-based payment platform.

## Requirements

- Flutter **3.27.0** or later
- Dart SDK (bundled with Flutter)
- Android Studio / Xcode for device/emulator support

## Package

- **Package ID (Android):** `io.zeropay.app`
- **Bundle ID (iOS):** `io.zeropay.app`

## Getting Started

### 1. Install dependencies

```bash
flutter pub get
```

### 2. Run on a connected device or emulator

```bash
flutter run
```

### 3. Build a release APK

```bash
flutter build apk --release
```

### 4. Build for iOS

```bash
flutter build ios --release
```

## Configuration

- Update `lib/backend/utils/api_endpoint.dart` with the ZeroPay backend URL.
- Replace `android/app/google-services.json` and `ios/Runner/GoogleService-Info.plist` with your Firebase project credentials.
- Update Pusher Beams instance ID in the app config with your ZeroPay Pusher instance.

## Resources

- [Flutter documentation](https://docs.flutter.dev/)
- [Lab: Write your first Flutter app](https://docs.flutter.dev/get-started/codelab)
- [Cookbook: Useful Flutter samples](https://docs.flutter.dev/cookbook)

