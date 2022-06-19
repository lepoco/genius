// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System;
using System.Text.Json;

namespace Genius.Client.Api;

/// <summary>
/// Unified response of the API.
/// </summary>
[Serializable]
public class RestResponse
{
    /// <summary>
    /// Response status.
    /// </summary>
    public RestStatus Status { get; set; }

    /// <summary>
    /// Whether the error occurred.
    /// </summary>
    public bool IsError { get; set; }

    /// <summary>
    /// Current timestamp.
    /// </summary>
    public long Timestamp { get; set; }

    /// <summary>
    /// Response message.
    /// </summary>
    public string Message { get; set; }

    /// <summary>
    /// Error message.
    /// </summary>
    public string ErrorMessage { get; set; }

    /// <summary>
    /// Response data.
    /// </summary>
    public object Result { get; set; }

    public RestResponse()
    {
        Status = RestStatus.Unknown;
        IsError = false;
        Message = String.Empty;
        ErrorMessage = String.Empty;
        Timestamp = RestTimestamp.Get();
        Result = String.Empty;
    }

    public override string ToString()
    {
        return JsonSerializer.Serialize(this);
    }
}
