// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System;

namespace Genius.Client.Api;

/// <summary>
/// Helper for response timestamps.
/// </summary>
public static class RestTimestamp
{
    /// <summary>
    /// Gets the generated UNIX timestamp;
    /// </summary>
    public static long Get()
    {
        return (long)DateTime.Now.Subtract(new DateTime(1970, 1, 1)).TotalSeconds;
    }
}

