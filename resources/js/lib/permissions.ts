export const permissionLabels: Record<string, string> = {
    'users.view': 'مشاهده کاربران',
    'users.create': 'ایجاد کاربر',
    'users.edit': 'ویرایش کاربران',
    'users.delete': 'حذف کاربران',
    'roles.manage': 'مدیریت نقش‌ها و دسترسی‌ها',
    'employees.view': 'مشاهده پرسنل',
    'employees.create': 'ایجاد پرسنل',
    'employees.edit': 'ویرایش پرسنل',
    'employees.delete': 'حذف پرسنل',
    'departments.view': 'مشاهده واحدهای سازمانی',
    'departments.manage': 'مدیریت واحدهای سازمانی',
    'positions.view': 'مشاهده سمت‌های شغلی',
    'positions.manage': 'مدیریت سمت‌های شغلی',
    'attendance.view': 'مشاهده حضور و غیاب',
    'attendance.manage': 'ثبت و ویرایش حضور و غیاب',
    'leave.view': 'مشاهده مرخصی‌ها',
    'leave.manage': 'مدیریت درخواست‌های مرخصی',
    'reports.view': 'مشاهده گزارش‌ها',
};

export const permissionGroups = [
    { title: 'کاربران و دسترسی‌ها', icon: '👤', keys: ['users.view', 'users.create', 'users.edit', 'users.delete', 'roles.manage'] },
    { title: 'پرسنل و سازمان', icon: '🏢', keys: ['employees.view', 'employees.create', 'employees.edit', 'employees.delete', 'departments.view', 'departments.manage', 'positions.view', 'positions.manage'] },
    { title: 'حضور، مرخصی و گزارش‌ها', icon: '🕒', keys: ['attendance.view', 'attendance.manage', 'leave.view', 'leave.manage', 'reports.view'] },
];

export const permissionLabel = (name: string) => permissionLabels[name] ?? name;
