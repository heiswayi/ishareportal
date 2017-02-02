# ishareportal

This repository is a backup of IsharePortal's source code. IsharePortal was the latest version of web portal, fully-featured and functional, totally improvised from [Ishare+](https://github.com/heiswayi/ishare-plus).

The project was discontinued and the source code might be DEPRECATED.

## History

IsharePortal was basically a web platform for a sharing community of students in USM Engineering Campus and established in February 2010. Originated from "Ishare", a simple WordPress-based site that just gathered all sharing servers (afterwards popularly known as sharerlinks) from all available sharers (anyone, student, staff, etc.) and sorted them in one place, so other people (users/downloaders) can easily found the sharerlinks. Ishare started to grow faster while in the meantime I kept improving the portal and finally came to a decision that needed a customized system.

So, I started to build the web portal from scratch using PHP, MySQL and jQuery and continued to improve it from time to time, feature by feature. There are two flagship features that made IsharePortal meaningful to the community;-

- Having our own fully AJAX shoutbox/chatbox application with our own choice of popular, funny and fun emoticons (Tuzki and Onion head club emoticons).
- Auto-check for online-offline of each sharerlink submitted.

At the final stage, IsharePortal was a complete CMS where came with User Profile system and fully integrated with [PunBB](https://github.com/punbb/punbb) forum system. _The source code of PunBB software included here is an altered version that I made specifically to integrate successfully with IsharePortal._

This is the final tagline used by IsharePortal and its community:

> For Sharers. By Sharers.

However, IsharePortal wasn't last long and here is the "sad" part of it...

### The Shutdown

Due to its popularity among the students and everyone else, IsharePortal project had produced some controversies on its own which classified to its pros and cons to keep operated. Creating high traffic load within the network (intranet) due to massive use of bandwidth and publicly sharing of illegal content such as pirated movies using university network were the most highlighted cons by the university administration (known as PPKT). Later, I decided to shut it down on March 12, 2013 effectively. If you're curious, here the [shutdown notice webpage](http://heiswayi.github.io/ishare-in-memory) I backed up.

Due to the community request, even though the main portal was shut down, I had created a simple script and I called it as a compact version of sharerlink system known as [iLink](https://github.com/heiswayi/ilink) to enable the sharers to continue promote their sharerlinks to the community. This script at least was able to reduce high traffic load and make it not so obvious on university administration radar. I'm not sure if iLink still be kept running until today or not as I already left my university a couple of years ago... I hope someone who administered it can keep contributing to the community.

## Screenshots

![IsharePortal Home](IsharePortal_home.png)
_Screenshot 1_

![IsharePortal Add Sharerlink](IsharePortal_addlink.png)
_Screenshot 2_

![IsharePortal Edit Sharerlink](IsharePortal_editlink.png)
_Screenshot 3_

![IsharePortal User Profile](IsharePortal_profile.png)
_Screenshot 4_

![IsharePortal Shoutbox](IsharePortal_shout.png)
_Screenshot 5_

![IsharePortal Shoutbox Emoticon Tuzki](IsharePortal_tuzki.png)
_Screenshot 6_

![IsharePortal Shoutbox Emoticon Onion](IsharePortal_onion.png)
_Screenshot 7_

## License

Please NOTE that, the Source Code might not follow any coding standard except for the forum and the code may look a quite messy. As for PHP 5.5.0, some code may be DEPRECATED. The Source Code is licensed under [MIT License](LICENSE). Some other code may subjected to copyrights to its respective owners or organizations (e.g. PunBB).

© Heiswayi Nrird
