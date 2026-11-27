# Security Policy

## Supported Versions

Security fixes are provided for the latest published version of the Scouting Nederland
WordPress theme. Older versions are not supported and should be upgraded before a report
is investigated.

The latest published version is available from:

- [GitHub Releases](https://github.com/scouting-nl/scouting-nederland-wordpress-theme/releases/latest)

When the theme is published in the WordPress.org directory, its latest directory release
will also be listed here.

## Report a Vulnerability Privately

Do not disclose suspected vulnerabilities in a public GitHub issue, discussion, pull
request, or WordPress.org support topic.

Use [GitHub private vulnerability reporting](https://github.com/scouting-nl/scouting-nederland-wordpress-theme/security/advisories/new)
to send the maintainers a confidential report. If GitHub private reporting is unavailable,
email [cms@support.scouting.nl](mailto:cms@support.scouting.nl?subject=Private%20security%20report%3A%20Scouting%20Nederland%20WordPress%20theme).

Include the following information when possible:

- The affected theme version and relevant WordPress, PHP, and web-server versions.
- The active theme settings, plugins, and user privileges required to reproduce the issue.
- A clear description of the impact and reproducible steps or a minimal proof of concept.
- Suggested remediation or mitigations, if known.
- Whether the issue has been disclosed to anyone else.

Remove passwords, API keys, personal data, client secrets, access tokens, and production
log contents from the report. If a secret has been exposed, revoke or rotate it before
reporting the incident.

## What to Expect

The maintainers aim to:

- Acknowledge a complete report within three business days.
- Provide an initial assessment within ten business days.
- Keep the reporter informed while a fix and release are prepared.
- Credit the reporter in the advisory or release notes unless anonymity is requested.

Please keep the report confidential until the maintainers confirm that patched installations
are publicly available and have had reasonable time to update. The maintainers will coordinate
the publication of an advisory when appropriate.

## Security Releases and Migration Guidance

Security release details remain in the private advisory until a fixed theme package has
been published and verified on GitHub Releases and, when applicable, WordPress.org. A version
bump or source change alone does not satisfy this publication requirement.

After patched installations are available, the maintainers will publish release and migration
guidance that includes:

- Affected and fixed versions.
- Any required backups, configuration changes, or upgrade prerequisites.
- Upgrade steps, including any action required from site administrators.
- A verification procedure for confirming that the fixed theme version is active.
- Temporary mitigations and rollback considerations, when applicable.
- A link to the coordinated security advisory and an appropriate support channel.

Until then, draft advisories, exploit details, and migration instructions that reveal the
vulnerability must remain private.

## Scope and Safe Testing

This policy covers the Scouting Nederland WordPress theme and its packaged releases, including
theme PHP, JavaScript, CSS, configuration, and bundled assets. The Scouting Nederland identity
provider, the separate `scouting-openid-connect` plugin, WordPress itself, hosting providers,
third-party dependencies, and unrelated plugins or services are outside this repository's
scope and should be reported to their respective owners.

Only test installations and accounts that you own or are explicitly authorized to use. Do not
access other users' data, degrade service availability, send unsolicited traffic, or retain
personal data obtained during testing.
