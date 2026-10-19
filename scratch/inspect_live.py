import subprocess

cmd = """ssh -p 65002 u664715641@46.202.186.86 "cd domains/buyle.id/public_html && php artisan tinker --execute='echo \\\"APP_ID: \\\" . App\\Services\\MetaConfigService::getAppId() . PHP_EOL; foreach(App\\Models\\InstagramAccount::all() as \\$a){ echo \\$a->username . \\\" | token: \\\" . substr(\\$a->access_token, 0, 15) . \\\"... len: \\\" . strlen(\\$a->access_token) . \\\" | page: \\\" . \\$a->page_id . \\\" | ig: \\\" . \\$a->instagram_business_id . PHP_EOL; } foreach(App\\Models\\CampaignLog::latest()->take(3)->get() as \\$l){ echo \\\"LOG: \\\" . \\$l->status . \\\" | \\\" . \\$l->error_message . PHP_EOL; }'\""""
res = subprocess.run(cmd, shell=True, capture_output=True, text=True)
print("STDOUT:", res.stdout)
print("STDERR:", res.stderr)
