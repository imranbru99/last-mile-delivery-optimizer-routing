@if (app()->environment(['local', 'demo', 'testing']))
    <div class="mt-6 rounded-xl border border-teal-200 bg-teal-50 p-4 text-sm dark:border-teal-800 dark:bg-teal-950">
        <p class="font-semibold text-teal-900 dark:text-teal-100">Demo Logins (password: <code>password</code>)</p>
        <ul class="mt-2 space-y-1 text-teal-800 dark:text-teal-200">
            <li>Super Admin: superadmin@demo.test</li>
            <li>SwiftParcel Owner: owner@swiftparcel.test</li>
            <li>Dispatcher: dispatcher@swiftparcel.test</li>
            <li>Driver: driver1@swiftparcel.test</li>
            <li>Merchant: merchant@swiftparcel.test</li>
            <li>FreshBasket Owner: owner@freshbasket.test</li>
            <li>UrbanChauffeur Owner: owner@urbanchauffeur.test</li>
        </ul>
    </div>
@endif
