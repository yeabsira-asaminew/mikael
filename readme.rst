remove the ; from ;extention=gd php.ini file


### Fix 1: Enable the Calendar Extension
1. Open your **`php.ini`** file (usually found in `xampp/php/`).
2. Find the following line (you can search for `;extension=calendar`):
   ```ini
   ;extension=calendar
   ```
3. Remove the semicolon (`;`) to uncomment it:
   ```ini
   extension=calendar
   ```
4. Restart your XAMPP server (Apache) to apply the changes.

---

### Alternative Fix: Implement a Custom JD to Gregorian Converter  
If enabling the extension is not an option, we can manually convert JD to Gregorian without using `jd_to_gregorian()`.

Let me know if you prefer this second approach!